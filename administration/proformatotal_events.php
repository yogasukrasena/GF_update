<?php
//BindEvents Method @1-0D9302E6
function BindEvents()
{
    global $Totalan;
    $Totalan->CCSEvents["BeforeShow"] = "Totalan_BeforeShow";
    $Totalan->CCSEvents["BeforeUpdate"] = "Totalan_BeforeUpdate";
}
//End BindEvents Method

//Totalan_BeforeShow @2-BF3379FC
function Totalan_BeforeShow(& $sender)
{
    $Totalan_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Totalan; //Compatibility
//End Totalan_BeforeShow

//Custom Code @19-2A29BDB7
$Proforma_H_ID = CCGetFromGet("Proforma_H_ID",0);
$TP = CCGetFromGet("TP",0);
$Totalan->SubTotal->SetValue($TP);
//End Custom Code

//Close Totalan_BeforeShow @2-1E576BF6
    return $Totalan_BeforeShow;
}
//End Close Totalan_BeforeShow

//Totalan_BeforeUpdate @2-459E8ACC
function Totalan_BeforeUpdate(& $sender)
{
    $Totalan_BeforeUpdate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Totalan; //Compatibility
//End Totalan_BeforeUpdate

//Custom Code @24-2A29BDB7
global $DBgayafusionall;
$Proforma_H_ID = CCGetFromGet("Proforma_H_ID",0);
$subtotal = $Totalan->SubTotal->GetValue();
$diskon = $Totalan->Discount->GetValue();
$packcost = $Totalan->Packaging->GetValue();
$shipcost = $Totalan->Fumigation->GetValue();
$grandtot = $Totalan->GrandTotal->GetValue();
//DEL  //if($Proforma_H_ID > 0){
$sql = "UPDATE ar_proforma SET SubTotal = ".$DBgayafusionall->ToSQL($subtotal,ccsFloat).", Discount = ".$DBgayafusionall->ToSQL($diskon,ccsFloat).", Packaging = ".$DBgayafusionall->ToSQL($packcost,ccsFloat).", Fumigation = ".$DBgayafusionall->ToSQL($shipcost,ccsFloat).", GrandTotal = ".$DBgayafusionall->ToSQL($grandtot,ccsFloat)." WHERE Proforma_H_ID = ".$DBgayafusionall->ToSQL($Proforma_H_ID,ccsInteger);
$DBgayafusionall->query($sql);
//DEL  //}
//End Custom Code

//Close Totalan_BeforeUpdate @2-0D22E396
    return $Totalan_BeforeUpdate;
}
//End Close Totalan_BeforeUpdate
?>