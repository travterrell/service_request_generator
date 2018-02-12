<?php
require_once dirname(dirname(dirname(__FILE__))).'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Request {

	public function checkProjectName($db, $projectName, $id, $type) {
		$db->startConnection();
		if ($type == "Edit") {
			$sql = "SELECT `project_name` FROM `service_requests` WHERE `id` = $id";
			$originalName = implode($db->runQuery($sql));
		}
		$sql = "SELECT `project_name` FROM `service_request_generator`.`service_requests`";
		$result = $db->runQuery($sql);
		foreach ($result as $row) {
			$row = implode($row);
			$count = 0;
			if ($type == "Edit" && $row == $originalName) {
				$count = 0;
			} else if ($row == $projectName) {
				$count++;
				break;
			}
		}
		$db->endConnection();
		return $count;
	}

	public function submitRequest($db, $inputs = array()) {
		$db->startConnection();

		$type = $inputs["type"];

		//Santize input data for insertion
		$agencyName = $db->sanitizeInput($inputs['agency_name']);
		$projectName = $db->sanitizeInput($inputs['project_name']);
		$budget = $db->sanitizeInput((float)$inputs['budget']);
		$primarySalesContact = $db->sanitizeInput($inputs['primary_sales_contact']);
		$customerContact = $db->sanitizeInput($inputs['customer_contact']);
		// $generalNotes = $db->sanitizeInput($inputs['general_notes']);
		$supplierCustomers = $db->sanitizeInput($inputs['supplier_customers']);
		$hqLocation = $db->sanitizeInput($inputs['hq_city']).", ".$db->sanitizeInput($inputs['hq_state']);
		$outsideEntity = $db->sanitizeInput($inputs['outside_entity']);
		$foreignCurrency = $db->sanitizeInput($inputs['foreign_currency']);
		$networkDiagram = $db->sanitizeInput($inputs['network_diagram']);
		$backupDiverse = $db->sanitizeInput($inputs['backup_diverse']);
		$voice = $db->sanitizeInput($inputs['voice']);

		if ($type == "New") {
			$sql = "INSERT INTO `service_requests` (`agency_name`, `project_name`, `budget`, `primary_sales_contact`, `customer_contact`, `customers`, `hq_location`, `outside_entity`, `foreign_currency`, `network_diagram`, `backup_diverse`, `voice`, `created_at`, `updated_at`) VALUES ('$agencyName', '$projectName', '$budget', '$primarySalesContact', '$customerContact', '$supplierCustomers', '$hqLocation', '$outsideEntity', '$foreignCurrency', '$networkDiagram', '$backupDiverse', '$voice', NOW(), NOW());";
		} else {
			$requestID = $inputs["id"];
			$sql = "UPDATE `service_requests` 
					SET `agency_name` = '$agencyName',
				    `project_name` = '$projectName',
				    `budget` = '$budget',
				    `primary_sales_contact` = '$primarySalesContact',
				    `customer_contact` = '$customerContact',
				    `customers` = '$supplierCustomers',
				    `hq_location` = '$hqLocation',
				    `outside_entity` = '$outsideEntity',
				    `foreign_currency` = '$foreignCurrency',
				    `network_diagram` = '$networkDiagram',
				    `backup_diverse` = '$backupDiverse',
				    `voice` = '$voice',
				    `updated_at` = NOW()
					WHERE `id` = '$requestID';";
		}

		if ($db->runQuery($sql) === TRUE) {
			if ($type == "New") {
				$message = "New service request<br>created successfully!";
				$sql = "SELECT `id` FROM `service_requests` WHERE `project_name` = '$projectName'";
				$requestID = implode($db->runQuery($sql));
			} else {
				$message = "Service request<br>updated successfully!";
			} 
		} else {
		    $message = "Error: " . $sql . "<br>" . $this->connection->error;
		}

		$results = array(
			"message" => $message,
			"project_name" => $projectName,
			"request_id" => $requestID,
		);

		$db->endConnection();
		return json_encode($results);
	}

	public function viewRequests($db) {
		$db->startConnection();
		$sql = "SELECT * FROM `service_requests` ORDER BY `created_at` DESC;";
		$result = $db->runQuery($sql);
		$db->endConnection();
		return $result;
	}

	public function viewRequest($db, $requestID) {
		$db->startConnection();
		$sql = "SELECT * FROM `service_requests` WHERE `id` = $requestID";
		$result = $db->runQuery($sql);
		$result["created_at"] = $db->formatDate($result["created_at"]);
		$result['budget'] = "$".number_format($result['budget'],2);
		$db->endConnection();
		return json_encode($result);
	}

	public function exportRequest($db, $type, $requestID) {
		$db->startConnection();
		$sql = "SELECT * FROM `service_requests` WHERE `id` = '$requestID'";
		$row = $db->runQuery($sql);
		$db->endConnection();

		// Format budget for the report
		$row["budget"] = "$".number_format($row['budget'],2);

		// Format the date for the export //
		$row["created_at"] = $db->formatDate($row["created_at"]);

		if ($type == "word") {
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$section = $phpWord->addSection();

			$headerFormat = array(
				"font" => array('name' => 'Century Gothic', 'size' => 26),
				"alignment" => array('alignment' => 'center'),
			);
			$agencyNameFormat = array(
				"font" => array('name' => 'Century Gothic', 'size' => 14, 'bold' => true),
				"alignment" => array('alignment' => 'center'),
			);
			$projectNameFormat = array(
				"font" => array('name' => 'Century Gothic', 'size' => 26, 'bold' => true, 'color' => '0e67bd'),
				"alignment" => array('alignment' => 'center'),
			);
			$notesTitleFormat = array(
				"font" => array('name' => 'Calibri', 'size' => 24, 'bold' => true, 'color' => '0e67bd'),
				"alignment" => array('alignment' => 'center'),
			);

			// Header Section
			$header = $section->addHeader();
			$header->addText("Telecom Service Request", $headerFormat['font'], $headerFormat['alignment']);
			$section->addText('');

			// Agency Name and Logo
			$agencyLogo = strtolower($row['agency_name']);
			$agencyLogo = str_replace(" ","_",$agencyLogo);
			if ($agencyLogo == "apple") {
				$width = 100;
				$height = 122;
			} else if ($agencyLogo == "ford") {
				$width = 200;
				$height = 77;
			} else {
				$width = 100;
				$height = 100;
			}
			$section->addImage(dirname(dirname(__FILE__))."/images/".$agencyLogo."_logo.png", array('alignment' => 'center', 'width' => $width, 'height' => $height));
			$section->addText($row['agency_name'], $agencyNameFormat['font'], $agencyNameFormat['alignment']);
			$section->addText('');

			//Main Project Info
			$section->addText($row['project_name'], $projectNameFormat['font'], $projectNameFormat['alignment']);
			$table = $section->addTable(array('width' => 6000, 'alignment' => 'center'));

			$mainInfo = array(
				"CREATED DATE" => $row["created_at"],
				"BUDGET" => $row["budget"],
				"PRIMARY SALES CONTACT" => $row["primary_sales_contact"],
				"CUSTOMER CONTACT" => $row["customer_contact"],
				);
			$mainInfoFormat = array(
				"header_font" => array('name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => '0e67bd'),
				"header_alignment" => array('alignment' => 'center'),
				"value_font" => array('name' => 'Century Gothic', 'size' => 11),
				"value_alignment" => array('alignment' => 'center'),
			);
			foreach ($mainInfo as $key => $value) {
			  	$table->addRow();
				$table->addCell(3000)->addText($key, $mainInfoFormat['header_font'], $mainInfoFormat['header_alignment']);
				$table->addCell(3000)->addText($value, $mainInfoFormat['value_font'], $mainInfoFormat['value_alignment']);
			}
			$section->addText('');
			$section->addText('');

			// Notes Section
			$section->addText('Notes', $notesTitleFormat['font'], $notesTitleFormat['alignment']);

			$notesInfo = array(
				"Is the supplier already working with customers?" => $row["customers"],
				"Where are the corp headquarters located?" => $row["hq_location"],
				"Will the contract be executed by an outside entity?" => $row["outside_entity"],
				"Is foreign currency payment a requirement at any of the locations?" => $row["foreign_currency"],
				"Do you have a network diagram available of current and desired solution?" => $row["network_diagram"],
				"Is a backup and/or diverse connection required?" => $row["backup_diverse"],
				"Do you require any Voice (SIP/TDM) to be deployed with this solution?" => $row["voice"],
			);
			$notesInfoFormat = array(
				"header_font" => array('name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => '0e67bd'),
				"value_font" => array('name' => 'Century Gothic', 'size' => 11),
			);
			foreach ($notesInfo as $key => $value) {
			  	$section->addText($key, $notesInfoFormat['header_font']);
				$section->addText($value, $notesInfoFormat['value_font']);
				$section->addText('');
			}

			// Export Word Document
			header("Content-Description: File Transfer");
			header('Content-Disposition: attachment; filename="' . $row['project_name'] . '.docx"');
			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			header('Expires: 0');
			ob_clean();
			$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$xmlWriter->save("php://output");
			exit();
		} else if ($type == 'excel') {
			$spreadsheet = new Spreadsheet();
			$spreadsheet->setActiveSheetIndex(0);
			$sheet = $spreadsheet->getActiveSheet();
			
			// Sets request title
			$sheet->mergeCells('A1:I1');
			$headerStyle = array(
			    'font' => array('name' => 'Century Gothic','size' => 26),
			    'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER),
			);
			$sheet->setCellValue('A1', 'Telecom Service Request');
			$sheet->getStyle('A1')->applyFromArray($headerStyle);

			// Sets agency logo & name
			$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
			$agencyLogo = strtolower($row['agency_name']);
			$agencyLogo = str_replace(" ","_",$agencyLogo);
			if ($agencyLogo == "apple") {
				$width = 100;
				$height = 122;
				$rowHeight = 91;
			} else if ($agencyLogo == "ford") {
				$width = 200;
				$height = 77;
				$rowHeight = 57;
				$drawing->setOffsetX(-50);
			} else {
				$width = 100;
				$height = 100;
				$rowHeight = 75;
			}
			$sheet->getRowDimension(3)->setRowHeight($rowHeight);
			$sheet->getColumnDimension('E')->setWidth(12.5);
			$drawing->setName('Logo');
			$drawing->setDescription('Logo');
			$drawing->setPath(dirname(dirname(__FILE__))."/images/".$agencyLogo."_logo.png");
			$drawing->setHeight($height);
			$drawing->setWidth($width);
			$drawing->setCoordinates('E3');
			$drawing->setWorksheet($sheet);
			$sheet->mergeCells('A4:I4');
			$agencyNameStyle = array(
			    'font' => array(
			        'name' => 'Century Gothic',
			        'size' => 16,
			    ),
			    'alignment' => array(
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ),
			);
			$sheet->setCellValue('A4', $row['agency_name']);
			$sheet->getStyle('A4')->applyFromArray($agencyNameStyle);

			// Sets main project info
			$sheet->mergeCells('A6:I6');
			$projectNameStyle = array(
			    'font' => array(
			        'name' => 'Calibri',
			        'size' => 20,
			        'color' => array('rgb' => '0e67bd'),
			    ),
			    'alignment' => array(
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ),
			);
			$sheet->setCellValue('A6', strtoupper($row['project_name']));
			$sheet->getStyle('A6')->applyFromArray($projectNameStyle);

			//Main Info Questions and Answers
			$mainInfo = array(
		        'CREATED DATE' => $row["created_at"],
		        'BUDGET' => $row['budget'],
		        'PRIMARY SALES CONTACT' => $row['primary_sales_contact'],
		        'CUSTOMER CONTACT' => $row['customer_contact'],
			);
			$rowNumber = 7;
			foreach ($mainInfo as $key => $value) {
				$mainInfoQuestionsStyle = array(
				    'font' => array(
				        'name' => 'Century Gothic',
				        'size' => 11,
				        'color' => array('rgb' => '0e67bd'),
				        'bold' => true,
				    ),
				    'alignment' => array(
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				    ),
				);
				$sheet->setCellValue('C'.$rowNumber, $key);
				$sheet->getStyle('C'.$rowNumber)->applyFromArray($mainInfoQuestionsStyle);
				$mainInfoAnswersStyle = array(
				    'font' => array(
				        'name' => 'Calibri',
				        'size' => 11,
				    ),
				    'alignment' => array(
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				    ),
				);
				$sheet->setCellValue('F'.$rowNumber, $value);
				$sheet->getStyle('F'.$rowNumber)->applyFromArray($mainInfoAnswersStyle);
				$rowNumber++;
			}

			//Project Notes
			$rowNumber = 12;
			$notesInfo = array(
		        'Is the supplier already working with customers?' => $row['customers'],
		        'Where are the corp headquarters located?' => $row['hq_location'],
		        'Will the contract be executed by an outside entity?' => $row['outside_entity'],
		        'Is foreign currency payment a requirement at any of the locations?' => $row['foreign_currency'],
		        'Do you have a network diagram available of current and desired solution?' => $row['network_diagram'],
		        'Is a backup and/or diverse connection required?' => $row['backup_diverse'],
		        'Do you require any Voice (SIP/TDM) to be deployed with this solution?' => $row['voice'],
			);
			foreach ($notesInfo as $key => $value) {
				$notesInfoQuestionsStyle = array(
				    'font' => array(
				        'name' => 'Arial',
				        'size' => 11,
				        'color' => array('rgb' => '0e67bd'),
				    ),
				    'alignment' => array(
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				    ),
				);
				$sheet->mergeCells('A'.$rowNumber.':I'.$rowNumber);
				$sheet->setCellValue('A'.$rowNumber, $key);
				$sheet->getStyle('A'.$rowNumber)->applyFromArray($notesInfoQuestionsStyle);
				$rowNumber++;
				$mainInfoAnswersStyle = array(
				    'font' => array(
				        'name' => 'Arial',
				        'size' => 11,
				    ),
				    'alignment' => array(
				        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				    ),
				);
				$sheet->mergeCells('A'.$rowNumber.':I'.$rowNumber);
				$sheet->setCellValue('A'.$rowNumber, $value);
				$sheet->getStyle('A'.$rowNumber)->applyFromArray($mainInfoAnswersStyle);
				$rowNumber++;
			}

			//Export Excel file
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$row['project_name'].'.xlsx"');
			header('Cache-Control: max-age=0');
			ob_clean();
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit();

		}
	}

}

?>