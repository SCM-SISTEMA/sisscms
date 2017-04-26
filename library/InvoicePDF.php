<?php

/**
 * Created by PhpStorm.
 * User: Juninho
 * Date: 06/09/2016
 * Time: 08:52
 */
class InvoicePDF extends FPDF //Create a new class to contain the header/footer/etc. which extends FPDF
{
    public function Header()
    {


    }

    public function Footer()
    {
        //any footer stuff goes here
    }

    public function FillHeadInfo($arrInfo) //$info would be an array of the stuff to fill the small table at the top
    {
        $this->SetY(30); //reset the Y to the original, since we moved it down to write INVOICE
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,"Total de registros: ".count($arrInfo),0,1,'C');
        $this->Ln(8);
        $this->SetFillColor(224,224,224); //Set background of the cell to be that grey color
        $this->Cell(30,12,"Numero",1,0,'C',true);  //Write a cell 20 wide, 12 high, filled and bordered, with Order # centered inside, last argument 'true' tells it to fill the cell with the color specified
        $this->Cell(80,12,"Cliente",1,0,'C',true);
        $this->Cell(20,12,"Valor",1,0,'C',true);
        $this->Cell(35,12,"Vencimento",1,0,'C',true); //the 1 before the 'C' instead of 0 in previous lines tells it to move down by the height of the cell after writing this
        $this->Cell(35,12,"Pagamento",1,1,'C',true); //the 1 before the 'C' instead of 0 in previous lines tells it to move down by the height of the cell after writing this

        foreach($arrInfo as $info) {
            $this->Cell(30, 12, $info['nu_documento'], 1, 0, 'C');
            $this->Cell(80,12,limitar($info['no_razao_social'], 30),1,'L',false); //I assume the customer address info is broken up into multiple different pieces
            $this->Cell(20, 12, formatValShow($info['ds_valor'], 2), 1, 0, 'C');
            $this->Cell(35, 12, retornaDmy($info['dt_vencimento']), 1, 0, 'C');
            $this->Cell(35, 12, retornaDmy($info['dt_pagamento']), 1, 1, 'C');
        }


     }

    public function fillItems($items)
    {
        //You'd build the items list much the same way as above, using a foreach loop or whatever
        //Could also easily combine this function and the one above
    }
}