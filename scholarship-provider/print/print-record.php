<?php
  error_reporting(0);
  session_start();
  include("../include/conn.php");
  include("../include/function.php");
  require('fpdf/fpdf.php');
  class PDF extends FPDF {
    function Header() {
      // Logo
      //left top size
      $this->Image('../../assets/img/brand/blue.png', 7.5, 2, 50);
      // Arial bold 15
      $this->SetFont('Arial', 'B', 15);
      // Move to the right
      $this->Cell(5);
      // Line break
      $this->Ln(10);
    }

    function Footer() {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('Arial', 'I', 8);
      // Page number
      $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
  }
  if (isset($_POST['print']))  {
  $table=trim($_POST['table']);
  $status=trim($_POST['status']);
  $year=trim($_POST['year']);
  $sp_id=trim($_POST['sp_id']);
  // Create new FPDF instance
  $pdf = new PDF('P', 'mm', 'A4');
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->SetFont('Arial', '', 10);
    // Add table headers
    $get_sp=getrecord('scholarship_program',['id'],[$sp_id]);
    if ($status < 0) {
      $status_str='Disapproved';
    } elseif ($status == 0) {
      $status_str='Pending';
    } elseif ($status == 1) {
      $status_str='Processed';
    } elseif ($status == 2) {
      $status_str='Accepted';
    } elseif ($status == 3) {
      $status_str='Not Awarded';
    } elseif ($status == 4) {
      $status_str='Awarded';
    } elseif ($status == 5) {
      $status_str='Active';
    } elseif ($status == 6) {
      $status_str='Inactive';
    } elseif ($status == 7) {
      $status_str='Terminated';
    }
    $today=date('Y/m/d');
    $pdf-> Ln(1);
    $pdf->Cell(170); // Add empty cells to adjust the right margin
    $pdf->Cell(10, 0, strtoupper($today." "), 0, 1);
    $pdf->Cell(0, 0, strtoupper($get_sp['name'])." - ".strtoupper($status_str)." ".strtoupper($table)." LIST ", 0, 0);
    $pdf-> Ln(5);
    //
    if($table==='application'){
    $pdf->Cell(5, 5, '#', 1, 0, 'C');
    $pdf->Cell(45, 5, 'Student Name', 1, 0, 'C');
    $pdf->Cell(60, 5, 'Email', 1, 0, 'C');
    $pdf->Cell(55, 5, 'School', 1, 0, 'C');
    $pdf->Cell(25, 5, 'Applied Date', 1, 1, 'C');
    }
    if($table==='scholar'){
      $pdf->Cell(5, 5, '#', 1, 0, 'C');
      $pdf->Cell(45, 5, 'Student Name', 1, 0, 'C');
      $pdf->Cell(60, 5, 'Award', 1, 0, 'C');
      $pdf->Cell(55, 5, 'School', 1, 0, 'C');
      $pdf->Cell(25, 5, 'Date Awarded', 1, 1, 'C');
      }

    // Query database and add table rows
    if($table==='application'){
    $sql="SELECT `application`.*,
    `application`.`id` AS `application_id`,
    `application`.`status` AS `application_status`,
    `application`.`created_on` AS `application_date`,
    `student`.*,
    `user`.`profileImage` AS `user_image`
    FROM `application`
    INNER JOIN `student` ON `student`.`id`=`application`.`student_id`
    INNER JOIN `user` ON `user`.`username`=`student`.`username`
    WHERE  `application`.`sp_id`='".$sp_id."' AND `application`.`status`='".$status."' AND YEAR(`application`.`created_on`)='".$year."'";
    }
    if($table==='scholar'){
      $sql="SELECT 
            `scholar`.*,
            `scholar`.`id` AS `scholar_id`,
            `scholar`.`status` AS `scholar_status`,
            `scholar`.`created_on` AS `scholar_accepted`,
            `student`.*,
            `user`.`profileImage` AS user_image,
            `school`.`school_name`,
            `sp_grant`.`name` AS `grant_name`
            FROM `scholar`
            INNER JOIN `student` ON `student`.`id`=`scholar`.`student_id`
            INNER JOIN `user` ON `user`.`username`=`student`.`username`
            INNER JOIN `school` ON `school`.`id`=`scholar`.`school_id`
            INNER JOIN `sp_grant` ON `sp_grant`.`id`=`scholar`.`award_no`
            WHERE `scholar`.`sp_id`='".$sp_id."'						
            ";
    }
    $stmt=$con->prepare($sql);
    $result=$stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $no=count($rows);
    if(!empty($rows)|| $no>0){
      $i=1;
        foreach($rows as $row) {
          if($table==='application'){
          $pdf->Cell(5,5, $i, 1, 0, 'C');
          $pdf->Cell(45, 5, $row['firstname'].' '.$row['lastname'], 1, 0, 'C');
          $pdf->Cell(60, 5, $row['username'], 1, 0, 'C');
          $pdf->Cell(55, 5, $row['school_intended'], 1, 0, 'C');
          $pdf->Cell(25, 5, date('Y/m/d', strtotime($row['application_date'])), 1, 1, 'C');
          }
          if($table==='scholar'){
          $pdf->Cell(5,5, $i, 1, 0, 'C');
          $pdf->Cell(45, 5, $row['firstname'].' '.$row['lastname'], 1, 0, 'C');
          $pdf->Cell(60, 5, $row['grant_name'], 1, 0, 'C');
          $pdf->Cell(55, 5, $row['school_name'], 1, 0, 'C');
          $pdf->Cell(25, 5, date('Y/m/d', strtotime($row['scholar_accepted'])), 1, 1, 'C');
          }
          $i++;
        }
      // Output PDF
      $pdf->Output();
    }else{
      $pdf = new PDF();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial', '', 15);
      $pdf->SetTextColor(255,35,1);
      $pdf->Cell(0, 10, "*No Record Found", 0, 1);
      $pdf->SetTextColor(0,0,0);
      $pdf->Output();
    }
  }
  else{
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 15);
    $pdf->SetTextColor(255,35,1);
    $pdf->Cell(0, 10, "*No Record Found", 0, 1);
    $pdf->SetTextColor(0,0,0);
    $pdf->Output();
  }

  ?>