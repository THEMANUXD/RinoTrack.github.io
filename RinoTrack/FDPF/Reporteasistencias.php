<?php
session_start();
$_SESSION["numero_control"];
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
        $this->Image('logo.png', 270, 5, 20); //logo ,moverDerecha,moverAbajo,tamañoIMG
        $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(95); // Movernos a la derecha
        $this->SetTextColor(0, 0, 0); //color
        //creamos una celda o fila
        $this->Cell(110, 15, utf8_decode($dato_info->nombre . " " . $dato_info->apellido), 1, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
        $this->Ln(3); // Salto de línea
        $this->SetTextColor(103); //color

        /* UBICACION */
        $this->Cell(180);  // mover a la derecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, utf8_decode("Ubicación : " . $dato_info->numero_control), 0, 0, '', 0);
        $this->Ln(5);

        /* TELEFONO */
        $this->Cell(180);  // mover a la derecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(59, 10, utf8_decode("Teléfono : " . $dato_info->carrera), 0, 0, '', 0);
        $this->Ln(5);

        /* RUC */
        $this->Cell(180);  // mover a la derecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(85, 10, utf8_decode("RUC : " . $dato_info->semestre), 0, 0, '', 0);
        $this->Ln(10);



        /* TITULO DE LA TABLA */
        //color
        $this->SetTextColor(0, 95, 189);
        $this->Cell(100); // mover a la derecha
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS "), 0, 1, 'C', 0);
        $this->Ln(7);

        /* CAMPOS DE LA TABLA */
        //color
        $this->SetFillColor(125, 173, 221); //colorFondo
        $this->SetTextColor(0, 0, 0); //colorTexto
        $this->SetDrawColor(163, 163, 163); //colorBorde
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
        $this->Cell(80, 10, utf8_decode('EMPLEADO'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('DNI'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('CARGO'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('SALIDA'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
        $hoy = date('d/m/Y');
        $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
    }
}

include("../CONEXION/conexion.php");
/* CONSULTA INFORMACION DEL HOSPEDAJE */

$pdf = new PDF();
$pdf->AddPage("landscape"); /* aqui entran dos para parametros (orientacion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal)*/
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

$consulta_reporte_asistencia = $conexion->query("SELECT 
        fecha,
        hora_entrada,
        hora_salida,
        horas_dia,
        @acumulado := @acumulado + horas_dia AS horas_acumuladas,
        total_mes
    FROM (
        SELECT 
            DATE(r.entrada) AS fecha,
            TIME(MIN(r.entrada)) AS hora_entrada,
            TIME(MAX(r.salida)) AS hora_salida,
            IFNULL(SEC_TO_TIME(TIMESTAMPDIFF(SECOND, r.entrada, r.salida)), '00:00:00') AS horas_dia,
            SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, r.entrada, r.salida)) OVER ()) AS horas_acumuladas
        FROM registro_horas r
        WHERE 
            r.numero_control = '$numero_control'
            AND MONTH(r.entrada) = $mes
            AND YEAR(entrada) = YEAR(CURDATE())
            AND r.salida IS NOT NULL
        GROUP BY DATE(r.entrada)
    ) AS dias, 
    (SELECT @acumulado := 0) AS vars
    ORDER BY fecha;");

// while ($datos_reporte = $consulta_reporte_asistencia->fetch_object()) {
// $i = $i + 1;
/* FILAS DE LA TABLA */
$pdf->Cell(30, 7, utf8_decode($datos_reporte->fecha), 1, 0, 'C', 0);
$pdf->Cell(30, 7, utf8_decode($datos_reporte->entrada), 1, 0, 'C', 0);
$pdf->Cell(30, 7, utf8_decode($datos_reporte->salida), 1, 0, 'C', 0);
$pdf->Cell(30, 7, utf8_decode($datos_reporte->horas_dia), 1, 0, 'C', 0);
$pdf->Cell(45, 7, utf8_decode($datos_reporte->horas_acumuladas), 1, 0, 'C', 0);
$pdf->Cell(25, 7, utf8_decode(" "), 1, 1, 'C', 0); // Última celda de la fila
//}

/* FILA DEL TOTAL (fuera del ciclo) */
$pdf->Cell(165, 7, utf8_decode('Total de horas en el mes:'), 1, 0, 'C', 0); // Suma del ancho de las primeras 5 celdas: 30+30+30+30+45 = 165
$pdf->Cell(25, 7, utf8_decode($total_horas_mes), 1, 1, 'C', 0);
// }


$pdf->Output('Reporte Asistencia.pdf', 'I'); //nombreDescarga, Visor(I->visualizar - D->descargar)
