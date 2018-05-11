<?php
$data=json_decode(file_get_contents('data.json'),true);

$ticket = $data['ticket'];

include('../phpinvoice.php');
$invoice = new phpinvoice();

  /* Header Settings */
  $invoice->setLogo("images/neolegal.png");
  $invoice->setColor("#007fff");
  $invoice->setType("Facture");
  $invoice->setReference("INV-".$ticket);
  $invoice->setDate(date('M dS ,Y',($data['date']) ? strtotime($data['date']) :time()));
  //$invoice->setTime(date('h:i:s A',time()));
  //$invoice->setDue(date('M dS ,Y',strtotime('+3 months')));
  
  
  
  $invoice->setFrom(array("Neolegal","TPS: 721714293RT0001","TVQ: 1224587933TQ0001","www.neolegal.ca",""));
  $invoice->setTo(array($data['name'],$data['address1'],$data['address2'],$data['country'],""));
  /* Adding Items in table */
  if (is_array($data['items'])) {
    foreach ($data['items'] as $key => $item) {
      $invoice->addItem($item['title'],$item['description'],$item['quantity'],(($item['taxable']) ? true:false),$item['price'],false);    
    }
  }
  
  //$invoice->addItem("Forfait petites créances","",1,false,333.43,false,333.43);
  /* Add totals */
  $invoice->addTotal("Non Taxable",0,5);
  $invoice->addTotal("Taxable",0,6);
  $invoice->addTotal("Total partiel",0,2);
  $invoice->addTotal("TPS (5%)",0.05,3);
  $invoice->addTotal("TVQ (9,975%)",0.09975,3);  
  $invoice->addTotal("Total commande",0,4);
  /* Set badge */ 
  $invoice->addBadge("PAYÉ");
  /* Add title */
  $invoice->addTitle("");
  /* Add Paragraph */
  $invoice->addParagraph("");
  /* Set footer note */
  $invoice->setFooternote("Neolegal www.neolegal.ca");
  /* Render */
  $invoice->render($ticket.'.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
?>