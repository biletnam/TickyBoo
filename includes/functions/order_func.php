<?php
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 */
require_once("classes/MyCart.php");
require_once("classes/Order.php");
require_once("classes/Ticket.php");

/*
function command ($order,$sid,$user_id,$trx=TRUE){
  require_once "classes/Place.php";
  
  foreach($order->places as $ticket){
  
    $places[]=array(
      'place_id'=>$ticket->place_id,
      'event_id'=>$ticket->event_id,
      'category_id'=>$ticket->category_id);
  }
  
  return Place::command($sid,$places,$user_id,$trx);
}
*/


//loads tickets and apply templates
//21dec2004: templates are  optional

function print_order ($order_id,$bill_template='',$mode='file',$print=FALSE, $subj=3){ //print subj: 1=tickets, 2=invoice, 3=both
  
	global $_SHOP;
	
  $query = 'SELECT * FROM Seat LEFT JOIN 
      Discount ON seat_discount_id=discount_id, 
      Event FORCE KEY ( PRIMARY ) , 
      Ort FORCE KEY ( PRIMARY ) ,
      User FORCE KEY ( PRIMARY ) ,
      Category FORCE KEY ( PRIMARY ) ,
      `Order` FORCE KEY ( PRIMARY ) ,
			Handling FORCE KEY ( PRIMARY ) '
  . " WHERE seat_order_id = ".ShopDB::quote($order_id)." AND 
      event_id = seat_event_id AND 
      ort_id = event_ort_id AND 
      user_id = seat_user_id AND 
      category_id = seat_category_id AND
			order_handling_id=handling_id AND
      order_id = ".ShopDB::quote($order_id);

  //echo $query;

  if(!$res=ShopDB::query($query)){
    user_error(shopDB::error());
    return FALSE;
  } 
    
  require_once("classes/TemplateEngine.php");
  require_once("html2pdf/html2pdf.class.php");
  require_once("admin/AdminView.php");
  require_once('classes/Handling.php');

	$first_page=TRUE;
	
  while($data=shopDB::fetch_assoc($res)){

		if(!isset($te)){
		  $hand=Handling::load($data['order_handling_id']);
			
			if($hand->pdf_paper_size){
				$paper_size=$hand->pdf_paper_size;
				$paper_orientation=$hand->pdf_paper_orientation;
			}else{	
				$paper_size=$_SHOP->pdf_paper_size;
				$paper_orientation=$_SHOP->pdf_paper_orientation;
			}
			$te  = new TemplateEngine();
			$pdf = new html2pdf(($paper_orientation=="portrait")?'P':'L', $paper_size, $_SHOP->lang);
		}

    //foreach ticket - choose the template
    
		if($hand->handling_pdf_ticket_template){
      $tpl_id=$hand->handling_pdf_ticket_template;
		}else if($data['category_template']){
      $tpl_id=$data['category_template'];
    }else if($data['event_template']){
      $tpl_id=$data['event_template'];
    }else{
		  $tpl_id=false;
		}

  	$country= new AdminView();
		$data['user_country_name']=$country->getCountry($data['user_country']);
    $country->free;
    
		
    if($tpl_id and ($subj & 1)){
			//load the template
			if(!$tpl =& $te->getTemplate($tpl_id)){
				user_error(no_template.": name: {$tpl_id} cat: {$data['category_id']}, event: {$data['event_id']}");
				return FALSE;
			}
		
			if($data['category_numbering']=='none'){
				$data['seat_nr']='0';
				$data['seat_row_nr']='0';
			}else if($data['category_numbering']=='rows'){
				$data['seat_nr']='0';
			}else if($data['category_numbering']=='seat'){
				$data['seat_row_nr']='0';
			}
		
			//compute  barcode
			$data['barcode_text']=
				sprintf("%08d%s",
								$data['seat_id'],
					$data['seat_code']);
		
			if(!$first_page){
				$pdf->setNewPage();
			}
			$first_page=FALSE;

			//print the ticket
			$tpl->write($pdf,$data);
		}
		
		$last_data=$data; 

    //save the data for the bill
    $key = "({$data['category_id']},{$data['discount_id']})";
    
    if(!isset($bill[$key])){
      $bill[$key]=array(
        'event_name'=>$data['event_name'],
        'event_date'=>$data['event_date'],
        'ort_name'=>$data['ort_name'],
        'ort_city'=>$data['ort_city'],
				'qty'=>1,
				'category_name'=>$data['category_name'],
				'seat_price'=>$data['seat_price'],
				'discount_name'=>$data['discount_name']
      );
    }else{
      $bill[$key]['qty']++;
    } 
  }

  //calculating the sub-total
  foreach(array_keys($bill) as $key){
    $bill[$key]['total']=$bill[$key]['seat_price']*$bill[$key]['qty'];
    $total_0+=$bill[$key]['total'];
  }

   
  $last_data['bill_data']=$bill; 
  $last_data['total_0']=$total_0;
  
  $last_data['fee']=$last_data['order_fee'];
  $last_data['total']=$total_0+$last_data['fee'];

	if(!isset($te)){
		$hand=Handling::load($data['order_handling_id']);
		
		if($hand->pdf_paper_size){
			$paper_size=$hand->pdf_paper_size;
			$paper_orientation=$hand->pdf_paper_orientation;
		}else{	
			$paper_size=$_SHOP->pdf_paper_size;
			$paper_orientation=$_SHOP->pdf_paper_orientation;
		}
		$te = new TemplateEngine();  
		$pdf = new html2pdf(($paper_orientation=="portrait")?'P':'L', $paper_size, $_SHOP->lang);
	}


	if(!$bill_template){
		$bill_template=$hand->handling_pdf_template;
	}	
	
	if($bill_template and ($subj & 2)){

		//loading the template 
		if($tpl =& $te->getTemplate($bill_template)){

			if(!$first_page){
				$pdf->setNewPage();
			}
			$first_page=FALSE;

			//applying the template
			$tpl->write($pdf,$last_data);
		}else{
			echo "<div class=err>".no_template." : $bill_template </div>";        
			return FALSE;
		}
	}  
 
  //composing filename without extension
  $order_file_name = "order_".$order_id.'.pdf';
 
  //producing the output
  if($mode=='file'){
    $pdf->output($_SHOP->ticket_dir.DS.$order_file_name, 'F');
  }else if($mode=='stream'){
    if($print){
      $pdf->output($order_file_name, 'P');
    }else{
      $pdf->output($order_file_name, 'I');
    }
  }else if($mode=='data'){
    $pdf_data=$pdf->output($order_file_name, 'S');
  }
  return $pdf_data;
}


?>