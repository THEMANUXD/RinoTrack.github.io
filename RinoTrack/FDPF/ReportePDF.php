<?php
session_start();
$_SESSION["numero_control"];
$nctrl = $_SESSION["numero_control"]; // Define la variable global
require('./fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      include("../CONEXION/conexion.php"); //llamamos a la conexion BD
      $nctrl = $_SESSION["numero_control"];
      $consulta_info  = $conexion->query("SELECT * FROM alumno 
         WHERE numero_control = '$nctrl';"); //traemos datos desde la BD
      $dato_info = $consulta_info->fetch_object();
      $this->Image('logo.png', 185, 5, 20); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      $this->Ln(3);
      //creamos una celda o fila
      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("DIVISION DE INGENIWERIA EN TECNOLOGIAS DE LA INFORMACION Y COMUNICACIONES"), 0, 0, '', 0);
      $this->Ln(5);


      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("Nombre :  " . $dato_info->nombre . " " . $dato_info->apellido), 0, 0, '', 0);
      $this->Ln(5);


      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(59, 10, utf8_decode("Carrera : " . $dato_info->carrera), 0, 0, '', 0);
      $this->Ln(5);


      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("No. control: " . $dato_info->numero_control), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("Semestre : " . $dato_info->semestre), 0, 0, '', 0);
      $this->Ln(10);

      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(0, 0, 0);
      $this->Cell(50); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS "), 0, 1, 'C', 0);
      $this->Ln(2);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(172, 172, 172); //colorFondo
      $this->SetTextColor(0, 0, 0); //colorTexto
      $this->SetDrawColor(0, 0, 0); //colorBorde
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(8, 8, utf8_decode('NO.'), 1, 0, 'C', 1);
      $this->Cell(31, 8, utf8_decode('FECHA'), 1, 0, 'C', 1);
      $this->Cell(31, 8, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
      $this->Cell(31, 8, utf8_decode('SALIDA'), 1, 0, 'C', 1);
      $this->Cell(31, 8, utf8_decode('HORAS/DIA'), 1, 0, 'C', 1);
      $this->Cell(38, 8, utf8_decode('Hrs ACUMULADAS'), 1, 0, 'C', 1);
      $this->Cell(25, 8, utf8_decode('FIRMA'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-20); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'B', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('_________________________________________________________________'), 0, 0, 'C');
      $this->Ln(2);
      $this->SetY(-15);
      $this->SetFont('Arial', 'B', 8); //tipo fuente, cursiva, tamañoTexto
      $this->Cell(0, 10, utf8_decode('NOMBRE Y FIRMA DEL RESPONSABLE DEL AREA Y SELLO DE LA DEPENDENCIA'), 0, 1, 'C'); // pie de pagina(fecha de pagina)
   }
}

include '../CONEXION/conexion.php';
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (orientacion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 10);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

$consulta_repxfecha = $conexion->query("SELECT 
    fecha,
    TIME_FORMAT(MIN(entrada), '%H:%i:%s') AS hora_entrada,
    TIME_FORMAT(MAX(salida), '%H:%i:%s') AS hora_salida,
    TIME_FORMAT(SEC_TO_TIME(segundos_dia), '%H:%i:%s') AS horas_dia,
    TIME_FORMAT(SEC_TO_TIME(@acumulado := @acumulado + segundos_dia), '%H:%i:%s') AS horas_acumuladas,
    TIME_FORMAT(SEC_TO_TIME(total_mes), '%H:%i:%s') AS total_mes
FROM (
    SELECT 
        DATE(entrada) AS fecha,
        entrada,
        salida,
        SUM(TIMESTAMPDIFF(SECOND, entrada, salida)) AS segundos_dia
    FROM registro_horas
    WHERE numero_control = '$nctrl'
        AND MONTH(entrada) = $mes
        AND YEAR(entrada) = YEAR(CURDATE())
        AND salida IS NOT NULL
    GROUP BY DATE(entrada)
) AS dias_detalle
JOIN (
    SELECT 
        SUM(TIMESTAMPDIFF(SECOND, entrada, salida)) AS total_mes
    FROM registro_horas
    WHERE numero_control = '$nctrl'
        AND MONTH(entrada) = $mes
        AND YEAR(entrada) = YEAR(CURDATE())
) AS total
CROSS JOIN (SELECT @acumulado := 0) AS vars
GROUP BY fecha
ORDER BY fecha;");

$total_mes = '00:00:00'; // Valor por defecto
while ($datos_reporte = $consulta_repxfecha->fetch_object()) {
   $i++;
   $pdf->Cell(8, 7, utf8_decode($i), 1, 0, 'C', 0);
   $pdf->Cell(31, 7, utf8_decode($datos_reporte->fecha), 1, 0, 'C', 0);
   $pdf->Cell(31, 7, utf8_decode($datos_reporte->hora_entrada), 1, 0, 'C', 0);
   $pdf->Cell(31, 7, utf8_decode($datos_reporte->hora_salida), 1, 0, 'C', 0);
   $pdf->Cell(31, 7, utf8_decode($datos_reporte->horas_dia), 1, 0, 'C', 0);
   $pdf->Cell(38, 7, utf8_decode($datos_reporte->horas_acumuladas), 1, 0, 'C', 0);
   $pdf->Cell(25, 7, utf8_decode(""), 1, 1, 'C', 0);
   $total_mes = $datos_reporte->horas_acumuladas;
}
$pdf->Cell(170, 7, utf8_decode('Total de horas en el mes:'), 1, 0, 'C', 0);
$pdf->Cell(25, 7, utf8_decode($total_mes), 1, 1, 'C', 0);


$pdf->Output('Prueba.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)
