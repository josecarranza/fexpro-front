<?php 
require 'PHPSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\ConditionalOperator;
use PhpOffice\PhpSpreadsheet\Style\ConditionalType;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function export_phpspreadsheet($data,$headers=array(),$no_last_headers=false){

        $data =  array_map(function($item){
            unset($item['id']);
            return $item;
        },$data);

        $new_columns = isset($_GET['new_columns'])?true:false;

		$path1 = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/';
		

		$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';


		define('SITEPATH', str_replace('\\', '/', $path1));

		$dataMainHeader = array('Product image','Style name', 'Style sku', 'Division','Global brand','Brand', 'Gender','Category', 'Group','Product', 'Season','Collection','Date','Team','Player','Composition', 'Product logo','Size Chart',"Dimensions");
		if(count($headers)>0){
			$dataMainHeader = array_merge($dataMainHeader,$headers);
		}
        if(!$no_last_headers){
            if(!$new_columns){
                $last_headers=array('Selling Price',"Total Units","Total Value","Ordenado fábrica","Open units","Product status");
            }else{
                $last_headers=array('Selling Price',"Total Units","Total Value",'Price MX',"System Suggestion","MOQ","Stock Panamá","Stock China","Stock Future","Factory order status","Ordenado fábrica","Cost FOB","Supplier","Supplier code","PI#","Sourcing Office","Open units","% units");
            }
            $dataMainHeader = array_merge($dataMainHeader,$last_headers);
        }
		


		$objPHPExcel = new Spreadsheet(); 

		$sheet = $objPHPExcel->getActiveSheet();
		
		$sheet->setAutoFilter('A1:N1');

        // Build headers
        $j=0;
        foreach( $dataMainHeader as $i => $row )
        {

            $sheet->setCellValueByColumnAndRow(($i+1),1,$row);
            $sheet
            ->getColumnDimensionByColumn($i+2)
            ->setAutoSize(true);
            $sheet->getStyleByColumnAndRow(($i+1),1)->getFont()->setBold(true);
            $sheet->getStyleByColumnAndRow(($i+1),1)->applyFromArray( //Q1
                array(
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => array('rgb' => '0000000')
                    ),
                    'font'  => array(
                      'color' => array('rgb' => 'FFFFFF'),
                    )
                )
            );

			$sheet->getStyleByColumnAndRow(($i+1),1)
			->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('000000');


        }  

        $sheet->getColumnDimension("A")->setAutoSize(false);
        $sheet->getColumnDimension("A")->setWidth(24);

        $sheet->getColumnDimension("J")->setAutoSize(false);
        $sheet->getColumnDimension("J")->setWidth(40);


        // Build cells

        $rowCount = 0;

        while( $rowCount < count($data) ){ 

            $cell = $rowCount+2;

            $column=0;
            foreach( $data[$rowCount] as $key => $value ) {
                $columnChar=\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex((string)($column+1));
				
                //$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 

                $sheet->getStyleByColumnAndRow($column+1,$cell)->applyFromArray(
                    array(
                        'borders' => array(
                        'allborders' => array(
                            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                        )
                    )
                );

                $sheet->getRowDimension($cell)->setRowHeight(65);
                switch ($key) {
                    case 'image':
                    case 'image2':
                        $file = $upload_path.$value;
                        
                        if (file_exists($file) && is_file($file)) {

                            //$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing =  new PhpOffice\PhpSpreadsheet\Worksheet\Drawing(); 
                      
                            //Path to signature .jpg file

                            $signature = $file;

                            $objDrawing->setPath($signature);

                           // $objDrawing->setOffsetX(5);                     //setOffsetX works properly

                            //$objDrawing->setOffsetY(10);                     //setOffsetY works properly
                            

                            //$objDrawing->setCoordinates($columnChar.($cell));             //set image to cell 
							$objDrawing->setCoordinates2($columnChar.($cell));
							
							//$objDrawing->setEditAs("absolute");
							$objDrawing->setResizeProportional(false);

							$objDrawing->setWidth(80);
                            if($key == 'image2'){
                                $objDrawing->setOffsetX(80);
                                $objDrawing->setWidth(0);
                            }

							$objDrawing->setHeight(80);
							
                            //$objDrawing->getHyperlink()->setUrl('http://www.google.com');

                            $objDrawing->setWorksheet($sheet);  //save 

                        }
                        if($key == 'image2'){
                            $column--;
                        }
                        
                        break;
                    case 'suggestion':
                        $sheet->setCellValueByColumnAndRow($column+1,$cell, $value); 
                        // Crear reglas de formato condicional para cada valor
                        $conditionalA = new Conditional();
                        $conditionalA->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                        $conditionalA->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT);
                        //$conditionalA->addCondition("DROP"); // Valor "A" para comparar
                        $conditionalA->setText("DROP");
                        $conditionalA->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                        $conditionalA->getStyle()->getFill()->getEndColor()->setARGB('ff0000');

 

                        $conditionalB = new Conditional();
                        $conditionalB->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                        $conditionalB->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT);
                        $conditionalB->setText("STOCK"); // Valor "B" para comparar
                        $conditionalB->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                        $conditionalB->getStyle()->getFill()->getEndColor()->setARGB('ffff00'); // Color amarillo

                        $conditionalC = new Conditional();
                        $conditionalC->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                        $conditionalC->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT);
                        $conditionalC->setText("OK"); // Valor "C" para comparar
                        $conditionalC->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                        $conditionalC->getStyle()->getFill()->getEndColor()->setARGB('00ff00'); // Color verde

                       
                        $conditionalStyles = $sheet->getStyle($columnChar.$cell)->getConditionalStyles();

                        $conditionalStyles[] = $conditionalA;
                        $conditionalStyles[] = $conditionalB;
                        $conditionalStyles[] = $conditionalC;
                        $sheet->getStyle($columnChar.$cell)->setConditionalStyles($conditionalStyles);
                    break;
                    default:
                        

                        $sheet->setCellValueByColumnAndRow($column+1,$cell, $value); 

                        break;

                }
            if($key=="price" || $key=="subtotal"){
                $sheet->getStyle($columnChar.$cell)->getNumberFormat()->setFormatCode("\$#,##0.00");  //'0.00');
            }
          
          
         
            $column++;
            }     
            $rowCount++; 
        }

		
		@ob_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="export1.xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer = IOFactory::createWriter($objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit(); 
}

//export_phpspreadsheet([["juan","lopez"],["ramon","valdez"]],["nombre","apellido"]);
?>