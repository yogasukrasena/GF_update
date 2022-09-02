<?php
//Include Common Files @1-DE6E32CD
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "AddPackList.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
include_once(RelativePath . "/Services.php");
//End Include Common Files

class clsRecordAddNewHeader { //AddNewHeader Class @2-5850F9DE

//Variables @2-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @2-3955AD5E
    function clsRecordAddNewHeader($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record AddNewHeader/Error";
        $this->DataSource = new clsAddNewHeaderDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "AddNewHeader";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->PackingListNo = new clsControl(ccsTextBox, "PackingListNo", "Packing List No", ccsText, "", CCGetRequestParam("PackingListNo", $Method, NULL), $this);
            $this->PackingListNo->Required = true;
            $this->PackingListDate = new clsControl(ccsTextBox, "PackingListDate", "Packing List Date", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("PackingListDate", $Method, NULL), $this);
            $this->PackingListDate->Required = true;
            $this->DatePicker_PackListDate = new clsDatePicker("DatePicker_PackListDate", "AddNewHeader", "PackingListDate", $this);
            $this->Invoice_H_ID = new clsControl(ccsHidden, "Invoice_H_ID", "Invoice H ID", ccsInteger, "", CCGetRequestParam("Invoice_H_ID", $Method, NULL), $this);
            $this->OrderRef = new clsControl(ccsTextBox, "OrderRef", "Order Ref", ccsText, "", CCGetRequestParam("OrderRef", $Method, NULL), $this);
            $this->OrderRef->Required = true;
            $this->AddressID = new clsControl(ccsHidden, "AddressID", "AddressID", ccsInteger, "", CCGetRequestParam("AddressID", $Method, NULL), $this);
            $this->InvoiceAddressContact = new clsControl(ccsListBox, "InvoiceAddressContact", "Invoice Address", ccsInteger, "", CCGetRequestParam("InvoiceAddressContact", $Method, NULL), $this);
            $this->InvoiceAddressContact->DSType = dsTable;
            $this->InvoiceAddressContact->DataSource = new clsDBgayafusionall();
            $this->InvoiceAddressContact->ds = & $this->InvoiceAddressContact->DataSource;
            $this->InvoiceAddressContact->DataSource->SQL = "SELECT * \n" .
"FROM tbladminist_addressbook_contact {SQL_Where} {SQL_OrderBy}";
            list($this->InvoiceAddressContact->BoundColumn, $this->InvoiceAddressContact->TextColumn, $this->InvoiceAddressContact->DBFormat) = array("ContactId", "ContactName", "");
            $this->InvoiceAddressContact->DataSource->Parameters["expr16"] = 0;
            $this->InvoiceAddressContact->DataSource->wp = new clsSQLParameters();
            $this->InvoiceAddressContact->DataSource->wp->AddParameter("1", "expr16", ccsInteger, "", "", $this->InvoiceAddressContact->DataSource->Parameters["expr16"], "", false);
            $this->InvoiceAddressContact->DataSource->wp->Criterion[1] = $this->InvoiceAddressContact->DataSource->wp->Operation(opEqual, "AddressID", $this->InvoiceAddressContact->DataSource->wp->GetDBValue("1"), $this->InvoiceAddressContact->DataSource->ToSQL($this->InvoiceAddressContact->DataSource->wp->GetDBValue("1"), ccsInteger),false);
            $this->InvoiceAddressContact->DataSource->Where = 
                 $this->InvoiceAddressContact->DataSource->wp->Criterion[1];
            $this->lblInvoiceAddressContact = new clsControl(ccsTextBox, "lblInvoiceAddressContact", "lblInvoiceAddressContact", ccsText, "", CCGetRequestParam("lblInvoiceAddressContact", $Method, NULL), $this);
            $this->LinkChangeInvoiceContact = new clsControl(ccsLink, "LinkChangeInvoiceContact", "LinkChangeInvoiceContact", ccsText, "", CCGetRequestParam("LinkChangeInvoiceContact", $Method, NULL), $this);
            $this->LinkChangeInvoiceContact->Page = "AddInvoice.php";
            $this->DeliveryAddressContact = new clsControl(ccsListBox, "DeliveryAddressContact", "Delivery Address", ccsInteger, "", CCGetRequestParam("DeliveryAddressContact", $Method, NULL), $this);
            $this->DeliveryAddressContact->DSType = dsTable;
            $this->DeliveryAddressContact->DataSource = new clsDBgayafusionall();
            $this->DeliveryAddressContact->ds = & $this->DeliveryAddressContact->DataSource;
            $this->DeliveryAddressContact->DataSource->SQL = "SELECT * \n" .
"FROM tbladminist_addressbook_contact {SQL_Where} {SQL_OrderBy}";
            list($this->DeliveryAddressContact->BoundColumn, $this->DeliveryAddressContact->TextColumn, $this->DeliveryAddressContact->DBFormat) = array("ContactId", "ContactName", "");
            $this->DeliveryAddressContact->DataSource->Parameters["expr27"] = 0;
            $this->DeliveryAddressContact->DataSource->wp = new clsSQLParameters();
            $this->DeliveryAddressContact->DataSource->wp->AddParameter("1", "expr27", ccsInteger, "", "", $this->DeliveryAddressContact->DataSource->Parameters["expr27"], "", false);
            $this->DeliveryAddressContact->DataSource->wp->Criterion[1] = $this->DeliveryAddressContact->DataSource->wp->Operation(opEqual, "AddressID", $this->DeliveryAddressContact->DataSource->wp->GetDBValue("1"), $this->DeliveryAddressContact->DataSource->ToSQL($this->DeliveryAddressContact->DataSource->wp->GetDBValue("1"), ccsInteger),false);
            $this->DeliveryAddressContact->DataSource->Where = 
                 $this->DeliveryAddressContact->DataSource->wp->Criterion[1];
            $this->lblDeliveryAddressContact = new clsControl(ccsTextBox, "lblDeliveryAddressContact", "lblDeliveryAddressContact", ccsText, "", CCGetRequestParam("lblDeliveryAddressContact", $Method, NULL), $this);
            $this->LinkChangeDeliveryContact = new clsControl(ccsLink, "LinkChangeDeliveryContact", "LinkChangeDeliveryContact", ccsText, "", CCGetRequestParam("LinkChangeDeliveryContact", $Method, NULL), $this);
            $this->LinkChangeDeliveryContact->Page = "AddInvoice.php";
            $this->InvoiceAddress = new clsControl(ccsTextArea, "InvoiceAddress", "Address", ccsText, "", CCGetRequestParam("InvoiceAddress", $Method, NULL), $this);
            $this->DeliveryAddress = new clsControl(ccsTextArea, "DeliveryAddress", "Address", ccsText, "", CCGetRequestParam("DeliveryAddress", $Method, NULL), $this);
            $this->InvoicePhone = new clsControl(ccsTextBox, "InvoicePhone", "Phone", ccsText, "", CCGetRequestParam("InvoicePhone", $Method, NULL), $this);
            $this->InvoiceFax = new clsControl(ccsTextBox, "InvoiceFax", "Fax", ccsText, "", CCGetRequestParam("InvoiceFax", $Method, NULL), $this);
            $this->DeliveryPhone = new clsControl(ccsTextBox, "DeliveryPhone", "Phone", ccsText, "", CCGetRequestParam("DeliveryPhone", $Method, NULL), $this);
            $this->DeliveryFax = new clsControl(ccsTextBox, "DeliveryFax", "Fax", ccsText, "", CCGetRequestParam("DeliveryFax", $Method, NULL), $this);
            $this->DumyAddress1 = new clsControl(ccsListBox, "DumyAddress1", "DumyAddress1", ccsText, "", CCGetRequestParam("DumyAddress1", $Method, NULL), $this);
            $this->DumyAddress1->DSType = dsTable;
            $this->DumyAddress1->DataSource = new clsDBgayafusionall();
            $this->DumyAddress1->ds = & $this->DumyAddress1->DataSource;
            $this->DumyAddress1->DataSource->SQL = "SELECT * \n" .
"FROM tbladminist_addressbook {SQL_Where} {SQL_OrderBy}";
            list($this->DumyAddress1->BoundColumn, $this->DumyAddress1->TextColumn, $this->DumyAddress1->DBFormat) = array("AddressID", "Company", "");
            $this->DumyAddress2 = new clsControl(ccsListBox, "DumyAddress2", "DumyAddress2", ccsText, "", CCGetRequestParam("DumyAddress2", $Method, NULL), $this);
            $this->DumyAddress2->DSType = dsTable;
            $this->DumyAddress2->DataSource = new clsDBgayafusionall();
            $this->DumyAddress2->ds = & $this->DumyAddress2->DataSource;
            $this->DumyAddress2->DataSource->SQL = "SELECT * \n" .
"FROM tbladminist_addressbook {SQL_Where} {SQL_OrderBy}";
            list($this->DumyAddress2->BoundColumn, $this->DumyAddress2->TextColumn, $this->DumyAddress2->DBFormat) = array("AddressID", "Company", "");
            $this->PL_H_ID = new clsControl(ccsHidden, "PL_H_ID", "PL_H_ID", ccsInteger, "", CCGetRequestParam("PL_H_ID", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @2-1F14898E
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlPL_H_ID"] = CCGetFromGet("PL_H_ID", NULL);
    }
//End Initialize Method

//Validate Method @2-B60714EF
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->PackingListNo->Validate() && $Validation);
        $Validation = ($this->PackingListDate->Validate() && $Validation);
        $Validation = ($this->Invoice_H_ID->Validate() && $Validation);
        $Validation = ($this->OrderRef->Validate() && $Validation);
        $Validation = ($this->AddressID->Validate() && $Validation);
        $Validation = ($this->InvoiceAddressContact->Validate() && $Validation);
        $Validation = ($this->lblInvoiceAddressContact->Validate() && $Validation);
        $Validation = ($this->DeliveryAddressContact->Validate() && $Validation);
        $Validation = ($this->lblDeliveryAddressContact->Validate() && $Validation);
        $Validation = ($this->InvoiceAddress->Validate() && $Validation);
        $Validation = ($this->DeliveryAddress->Validate() && $Validation);
        $Validation = ($this->InvoicePhone->Validate() && $Validation);
        $Validation = ($this->InvoiceFax->Validate() && $Validation);
        $Validation = ($this->DeliveryPhone->Validate() && $Validation);
        $Validation = ($this->DeliveryFax->Validate() && $Validation);
        $Validation = ($this->DumyAddress1->Validate() && $Validation);
        $Validation = ($this->DumyAddress2->Validate() && $Validation);
        $Validation = ($this->PL_H_ID->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->PackingListNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->PackingListDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Invoice_H_ID->Errors->Count() == 0);
        $Validation =  $Validation && ($this->OrderRef->Errors->Count() == 0);
        $Validation =  $Validation && ($this->AddressID->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InvoiceAddressContact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->lblInvoiceAddressContact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryAddressContact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->lblDeliveryAddressContact->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InvoiceAddress->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryAddress->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InvoicePhone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InvoiceFax->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryPhone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryFax->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DumyAddress1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DumyAddress2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->PL_H_ID->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F54C7482
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->PackingListNo->Errors->Count());
        $errors = ($errors || $this->PackingListDate->Errors->Count());
        $errors = ($errors || $this->DatePicker_PackListDate->Errors->Count());
        $errors = ($errors || $this->Invoice_H_ID->Errors->Count());
        $errors = ($errors || $this->OrderRef->Errors->Count());
        $errors = ($errors || $this->AddressID->Errors->Count());
        $errors = ($errors || $this->InvoiceAddressContact->Errors->Count());
        $errors = ($errors || $this->lblInvoiceAddressContact->Errors->Count());
        $errors = ($errors || $this->LinkChangeInvoiceContact->Errors->Count());
        $errors = ($errors || $this->DeliveryAddressContact->Errors->Count());
        $errors = ($errors || $this->lblDeliveryAddressContact->Errors->Count());
        $errors = ($errors || $this->LinkChangeDeliveryContact->Errors->Count());
        $errors = ($errors || $this->InvoiceAddress->Errors->Count());
        $errors = ($errors || $this->DeliveryAddress->Errors->Count());
        $errors = ($errors || $this->InvoicePhone->Errors->Count());
        $errors = ($errors || $this->InvoiceFax->Errors->Count());
        $errors = ($errors || $this->DeliveryPhone->Errors->Count());
        $errors = ($errors || $this->DeliveryFax->Errors->Count());
        $errors = ($errors || $this->DumyAddress1->Errors->Count());
        $errors = ($errors || $this->DumyAddress2->Errors->Count());
        $errors = ($errors || $this->PL_H_ID->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @2-ED598703
function SetPrimaryKeys($keyArray)
{
    $this->PrimaryKeys = $keyArray;
}
function GetPrimaryKeys()
{
    return $this->PrimaryKeys;
}
function GetPrimaryKey($keyName)
{
    return $this->PrimaryKeys[$keyName];
}
//End MasterDetail

//Operation Method @2-E955BD63
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @2-97A4BFF0
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->PackingListNo->SetValue($this->PackingListNo->GetValue(true));
        $this->DataSource->PackingListDate->SetValue($this->PackingListDate->GetValue(true));
        $this->DataSource->Invoice_H_ID->SetValue($this->Invoice_H_ID->GetValue(true));
        $this->DataSource->OrderRef->SetValue($this->OrderRef->GetValue(true));
        $this->DataSource->AddressID->SetValue($this->AddressID->GetValue(true));
        $this->DataSource->InvoiceAddressContact->SetValue($this->InvoiceAddressContact->GetValue(true));
        $this->DataSource->lblInvoiceAddressContact->SetValue($this->lblInvoiceAddressContact->GetValue(true));
        $this->DataSource->LinkChangeInvoiceContact->SetValue($this->LinkChangeInvoiceContact->GetValue(true));
        $this->DataSource->DeliveryAddressContact->SetValue($this->DeliveryAddressContact->GetValue(true));
        $this->DataSource->lblDeliveryAddressContact->SetValue($this->lblDeliveryAddressContact->GetValue(true));
        $this->DataSource->LinkChangeDeliveryContact->SetValue($this->LinkChangeDeliveryContact->GetValue(true));
        $this->DataSource->InvoiceAddress->SetValue($this->InvoiceAddress->GetValue(true));
        $this->DataSource->DeliveryAddress->SetValue($this->DeliveryAddress->GetValue(true));
        $this->DataSource->InvoicePhone->SetValue($this->InvoicePhone->GetValue(true));
        $this->DataSource->InvoiceFax->SetValue($this->InvoiceFax->GetValue(true));
        $this->DataSource->DeliveryPhone->SetValue($this->DeliveryPhone->GetValue(true));
        $this->DataSource->DeliveryFax->SetValue($this->DeliveryFax->GetValue(true));
        $this->DataSource->DumyAddress1->SetValue($this->DumyAddress1->GetValue(true));
        $this->DataSource->DumyAddress2->SetValue($this->DumyAddress2->GetValue(true));
        $this->DataSource->PL_H_ID->SetValue($this->PL_H_ID->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-52D68D04
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->PackingListNo->SetValue($this->PackingListNo->GetValue(true));
        $this->DataSource->PackingListDate->SetValue($this->PackingListDate->GetValue(true));
        $this->DataSource->Invoice_H_ID->SetValue($this->Invoice_H_ID->GetValue(true));
        $this->DataSource->OrderRef->SetValue($this->OrderRef->GetValue(true));
        $this->DataSource->AddressID->SetValue($this->AddressID->GetValue(true));
        $this->DataSource->InvoiceAddressContact->SetValue($this->InvoiceAddressContact->GetValue(true));
        $this->DataSource->lblInvoiceAddressContact->SetValue($this->lblInvoiceAddressContact->GetValue(true));
        $this->DataSource->LinkChangeInvoiceContact->SetValue($this->LinkChangeInvoiceContact->GetValue(true));
        $this->DataSource->DeliveryAddressContact->SetValue($this->DeliveryAddressContact->GetValue(true));
        $this->DataSource->lblDeliveryAddressContact->SetValue($this->lblDeliveryAddressContact->GetValue(true));
        $this->DataSource->LinkChangeDeliveryContact->SetValue($this->LinkChangeDeliveryContact->GetValue(true));
        $this->DataSource->InvoiceAddress->SetValue($this->InvoiceAddress->GetValue(true));
        $this->DataSource->DeliveryAddress->SetValue($this->DeliveryAddress->GetValue(true));
        $this->DataSource->InvoicePhone->SetValue($this->InvoicePhone->GetValue(true));
        $this->DataSource->InvoiceFax->SetValue($this->InvoiceFax->GetValue(true));
        $this->DataSource->DeliveryPhone->SetValue($this->DeliveryPhone->GetValue(true));
        $this->DataSource->DeliveryFax->SetValue($this->DeliveryFax->GetValue(true));
        $this->DataSource->DumyAddress1->SetValue($this->DumyAddress1->GetValue(true));
        $this->DataSource->DumyAddress2->SetValue($this->DumyAddress2->GetValue(true));
        $this->DataSource->PL_H_ID->SetValue($this->PL_H_ID->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @2-670FE8F4
    function Show()
    {
        global $CCSUseAmp;
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->InvoiceAddressContact->Prepare();
        $this->DeliveryAddressContact->Prepare();
        $this->DumyAddress1->Prepare();
        $this->DumyAddress2->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->PackingListNo->SetValue($this->DataSource->PackingListNo->GetValue());
                    $this->PackingListDate->SetValue($this->DataSource->PackingListDate->GetValue());
                    $this->Invoice_H_ID->SetValue($this->DataSource->Invoice_H_ID->GetValue());
                    $this->OrderRef->SetValue($this->DataSource->OrderRef->GetValue());
                    $this->InvoiceAddressContact->SetValue($this->DataSource->InvoiceAddressContact->GetValue());
                    $this->DeliveryAddressContact->SetValue($this->DataSource->DeliveryAddressContact->GetValue());
                    $this->DumyAddress1->SetValue($this->DataSource->DumyAddress1->GetValue());
                    $this->PL_H_ID->SetValue($this->DataSource->PL_H_ID->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }
        if (!$this->FormSubmitted) {
        }
        $this->LinkChangeInvoiceContact->Parameters = CCGetQueryString("QueryString", array("InvoiceContactID", "ccsForm"));
        $this->LinkChangeInvoiceContact->Parameters = CCAddParam($this->LinkChangeInvoiceContact->Parameters, "AddressID", $this->DataSource->f("AddressID"));
        $this->LinkChangeInvoiceContact->Parameters = CCAddParam($this->LinkChangeInvoiceContact->Parameters, "Invoice_H_ID", $this->DataSource->f("Invoice_H_ID"));
        $this->LinkChangeDeliveryContact->Parameters = CCGetQueryString("QueryString", array("DeliveryContactID", "ccsForm"));
        $this->LinkChangeDeliveryContact->Parameters = CCAddParam($this->LinkChangeDeliveryContact->Parameters, "AddressID", $this->DataSource->f("AddressID"));
        $this->LinkChangeDeliveryContact->Parameters = CCAddParam($this->LinkChangeDeliveryContact->Parameters, "Invoice_H_ID", $this->DataSource->f("Invoice_H_ID"));

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->PackingListNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->PackingListDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_PackListDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Invoice_H_ID->Errors->ToString());
            $Error = ComposeStrings($Error, $this->OrderRef->Errors->ToString());
            $Error = ComposeStrings($Error, $this->AddressID->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InvoiceAddressContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->lblInvoiceAddressContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->LinkChangeInvoiceContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryAddressContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->lblDeliveryAddressContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->LinkChangeDeliveryContact->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InvoiceAddress->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryAddress->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InvoicePhone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InvoiceFax->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryPhone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryFax->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DumyAddress1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DumyAddress2->Errors->ToString());
            $Error = ComposeStrings($Error, $this->PL_H_ID->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->PackingListNo->Show();
        $this->PackingListDate->Show();
        $this->DatePicker_PackListDate->Show();
        $this->Invoice_H_ID->Show();
        $this->OrderRef->Show();
        $this->AddressID->Show();
        $this->InvoiceAddressContact->Show();
        $this->lblInvoiceAddressContact->Show();
        $this->LinkChangeInvoiceContact->Show();
        $this->DeliveryAddressContact->Show();
        $this->lblDeliveryAddressContact->Show();
        $this->LinkChangeDeliveryContact->Show();
        $this->InvoiceAddress->Show();
        $this->DeliveryAddress->Show();
        $this->InvoicePhone->Show();
        $this->InvoiceFax->Show();
        $this->DeliveryPhone->Show();
        $this->DeliveryFax->Show();
        $this->DumyAddress1->Show();
        $this->DumyAddress2->Show();
        $this->PL_H_ID->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End AddNewHeader Class @2-FCB6E20C

class clsAddNewHeaderDataSource extends clsDBgayafusionall {  //AddNewHeaderDataSource Class @2-B5B08D50

//DataSource Variables @2-4A11B9B6
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $PackingListNo;
    public $PackingListDate;
    public $Invoice_H_ID;
    public $OrderRef;
    public $AddressID;
    public $InvoiceAddressContact;
    public $lblInvoiceAddressContact;
    public $LinkChangeInvoiceContact;
    public $DeliveryAddressContact;
    public $lblDeliveryAddressContact;
    public $LinkChangeDeliveryContact;
    public $InvoiceAddress;
    public $DeliveryAddress;
    public $InvoicePhone;
    public $InvoiceFax;
    public $DeliveryPhone;
    public $DeliveryFax;
    public $DumyAddress1;
    public $DumyAddress2;
    public $PL_H_ID;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-ADD1E56A
    function clsAddNewHeaderDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record AddNewHeader/Error";
        $this->Initialize();
        $this->PackingListNo = new clsField("PackingListNo", ccsText, "");
        
        $this->PackingListDate = new clsField("PackingListDate", ccsDate, $this->DateFormat);
        
        $this->Invoice_H_ID = new clsField("Invoice_H_ID", ccsInteger, "");
        
        $this->OrderRef = new clsField("OrderRef", ccsText, "");
        
        $this->AddressID = new clsField("AddressID", ccsInteger, "");
        
        $this->InvoiceAddressContact = new clsField("InvoiceAddressContact", ccsInteger, "");
        
        $this->lblInvoiceAddressContact = new clsField("lblInvoiceAddressContact", ccsText, "");
        
        $this->LinkChangeInvoiceContact = new clsField("LinkChangeInvoiceContact", ccsText, "");
        
        $this->DeliveryAddressContact = new clsField("DeliveryAddressContact", ccsInteger, "");
        
        $this->lblDeliveryAddressContact = new clsField("lblDeliveryAddressContact", ccsText, "");
        
        $this->LinkChangeDeliveryContact = new clsField("LinkChangeDeliveryContact", ccsText, "");
        
        $this->InvoiceAddress = new clsField("InvoiceAddress", ccsText, "");
        
        $this->DeliveryAddress = new clsField("DeliveryAddress", ccsText, "");
        
        $this->InvoicePhone = new clsField("InvoicePhone", ccsText, "");
        
        $this->InvoiceFax = new clsField("InvoiceFax", ccsText, "");
        
        $this->DeliveryPhone = new clsField("DeliveryPhone", ccsText, "");
        
        $this->DeliveryFax = new clsField("DeliveryFax", ccsText, "");
        
        $this->DumyAddress1 = new clsField("DumyAddress1", ccsText, "");
        
        $this->DumyAddress2 = new clsField("DumyAddress2", ccsText, "");
        
        $this->PL_H_ID = new clsField("PL_H_ID", ccsInteger, "");
        

        $this->InsertFields["PLNo"] = array("Name" => "PLNo", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["PLDate"] = array("Name" => "PLDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["Invoice_H_ID"] = array("Name" => "Invoice_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["OrderRef"] = array("Name" => "OrderRef", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["InvoiceContactID"] = array("Name" => "InvoiceContactID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryContactID"] = array("Name" => "DeliveryContactID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["AddressID"] = array("Name" => "AddressID", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["PL_H_ID"] = array("Name" => "PL_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["PLNo"] = array("Name" => "PLNo", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["PLDate"] = array("Name" => "PLDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["Invoice_H_ID"] = array("Name" => "Invoice_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["OrderRef"] = array("Name" => "OrderRef", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["InvoiceContactID"] = array("Name" => "InvoiceContactID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryContactID"] = array("Name" => "DeliveryContactID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["AddressID"] = array("Name" => "AddressID", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["PL_H_ID"] = array("Name" => "PL_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-6D8ED84B
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlPL_H_ID", ccsInteger, "", "", $this->Parameters["urlPL_H_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "PL_H_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-6F4C96A5
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM tbladminist_packinglist_h {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-3F46A71D
    function SetValues()
    {
        $this->PackingListNo->SetDBValue($this->f("PLNo"));
        $this->PackingListDate->SetDBValue(trim($this->f("PLDate")));
        $this->Invoice_H_ID->SetDBValue(trim($this->f("Invoice_H_ID")));
        $this->OrderRef->SetDBValue($this->f("OrderRef"));
        $this->InvoiceAddressContact->SetDBValue(trim($this->f("InvoiceContactID")));
        $this->DeliveryAddressContact->SetDBValue(trim($this->f("DeliveryContactID")));
        $this->DumyAddress1->SetDBValue($this->f("AddressID"));
        $this->PL_H_ID->SetDBValue(trim($this->f("PL_H_ID")));
    }
//End SetValues Method

//Insert Method @2-75C824FC
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["PLNo"]["Value"] = $this->PackingListNo->GetDBValue(true);
        $this->InsertFields["PLDate"]["Value"] = $this->PackingListDate->GetDBValue(true);
        $this->InsertFields["Invoice_H_ID"]["Value"] = $this->Invoice_H_ID->GetDBValue(true);
        $this->InsertFields["OrderRef"]["Value"] = $this->OrderRef->GetDBValue(true);
        $this->InsertFields["InvoiceContactID"]["Value"] = $this->InvoiceAddressContact->GetDBValue(true);
        $this->InsertFields["DeliveryContactID"]["Value"] = $this->DeliveryAddressContact->GetDBValue(true);
        $this->InsertFields["AddressID"]["Value"] = $this->DumyAddress1->GetDBValue(true);
        $this->InsertFields["PL_H_ID"]["Value"] = $this->PL_H_ID->GetDBValue(true);
        $this->SQL = CCBuildInsert("tbladminist_packinglist_h", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-14F826EA
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["PLNo"]["Value"] = $this->PackingListNo->GetDBValue(true);
        $this->UpdateFields["PLDate"]["Value"] = $this->PackingListDate->GetDBValue(true);
        $this->UpdateFields["Invoice_H_ID"]["Value"] = $this->Invoice_H_ID->GetDBValue(true);
        $this->UpdateFields["OrderRef"]["Value"] = $this->OrderRef->GetDBValue(true);
        $this->UpdateFields["InvoiceContactID"]["Value"] = $this->InvoiceAddressContact->GetDBValue(true);
        $this->UpdateFields["DeliveryContactID"]["Value"] = $this->DeliveryAddressContact->GetDBValue(true);
        $this->UpdateFields["AddressID"]["Value"] = $this->DumyAddress1->GetDBValue(true);
        $this->UpdateFields["PL_H_ID"]["Value"] = $this->PL_H_ID->GetDBValue(true);
        $this->SQL = CCBuildUpdate("tbladminist_packinglist_h", $this->UpdateFields, $this);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

} //End AddNewHeaderDataSource Class @2-FCB6E20C

class clsEditableGridAddNewDetail { //AddNewDetail Class @42-254BF570

//Variables @42-F9538F3C

    // Public variables
    public $ComponentType = "EditableGrid";
    public $ComponentName;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormParameters;
    public $FormState;
    public $FormEnctype;
    public $CachedColumns;
    public $TotalRows;
    public $UpdatedRows;
    public $EmptyRows;
    public $Visible;
    public $RowsErrors;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode;
    public $ValidatingControls;
    public $Controls;
    public $ControlsErrors;
    public $RowNumber;
    public $Attributes;
    public $PrimaryKeys;

    // Class variables
//End Variables

//Class_Initialize Event @42-84948922
    function clsEditableGridAddNewDetail($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid AddNewDetail/Error";
        $this->ControlsErrors = array();
        $this->ComponentName = "AddNewDetail";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->CachedColumns["PL_D_ID"][0] = "PL_D_ID";
        $this->CachedColumns["PL_D_ID"][0] = "PL_D_ID";
        $this->DataSource = new clsAddNewDetailDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->EmptyRows = 31;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->PL_H_ID = new clsControl(ccsHidden, "PL_H_ID", "PL_H_ID", ccsInteger, "", NULL, $this);
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, $CCSLocales->GetFormatInfo("BooleanFormat"), NULL, $this);
        $this->CheckBox_Delete->CheckedValue = true;
        $this->CheckBox_Delete->UncheckedValue = false;
        $this->Button_Submit = new clsButton("Button_Submit", $Method, $this);
        $this->AddItemBtn = new clsButton("AddItemBtn", $Method, $this);
        $this->RowIDAttribute = new clsControl(ccsLabel, "RowIDAttribute", "RowIDAttribute", ccsText, "", NULL, $this);
        $this->RowStyleAttribute = new clsControl(ccsLabel, "RowStyleAttribute", "RowStyleAttribute", ccsText, "", NULL, $this);
        $this->RowStyleAttribute->HTML = true;
        $this->RowNameAttribute = new clsControl(ccsLabel, "RowNameAttribute", "RowNameAttribute", ccsText, "", NULL, $this);
        $this->Box_H_ID = new clsControl(ccsListBox, "Box_H_ID", "Box_H_ID", ccsInteger, "", NULL, $this);
        $this->Box_H_ID->DSType = dsTable;
        $this->Box_H_ID->DataSource = new clsDBgayafusionall();
        $this->Box_H_ID->ds = & $this->Box_H_ID->DataSource;
        $this->Box_H_ID->DataSource->SQL = "SELECT * \n" .
"FROM tbladminist_box_h {SQL_Where} {SQL_OrderBy}";
        list($this->Box_H_ID->BoundColumn, $this->Box_H_ID->TextColumn, $this->Box_H_ID->DBFormat) = array("Box_H_ID", "BoxNumber", "");
        $this->Box_H_ID->DataSource->Parameters["urlPL_H_ID"] = CCGetFromGet("PL_H_ID", NULL);
        $this->Box_H_ID->DataSource->wp = new clsSQLParameters();
        $this->Box_H_ID->DataSource->wp->AddParameter("1", "urlPL_H_ID", ccsInteger, "", "", $this->Box_H_ID->DataSource->Parameters["urlPL_H_ID"], "", false);
        $this->Box_H_ID->DataSource->wp->Criterion[1] = $this->Box_H_ID->DataSource->wp->Operation(opEqual, "PL_H_ID", $this->Box_H_ID->DataSource->wp->GetDBValue("1"), $this->Box_H_ID->DataSource->ToSQL($this->Box_H_ID->DataSource->wp->GetDBValue("1"), ccsInteger),false);
        $this->Box_H_ID->DataSource->Where = 
             $this->Box_H_ID->DataSource->wp->Criterion[1];
        $this->AddBox = new clsControl(ccsImageLink, "AddBox", "AddBox", ccsText, "", NULL, $this);
        $this->AddBox->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->AddBox->Page = "";
    }
//End Class_Initialize Event

//Initialize Method @42-BD137E99
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);

        $this->DataSource->Parameters["urlPL_H_ID"] = CCGetFromGet("PL_H_ID", NULL);
        $this->DataSource->Parameters["urlBox_H_ID"] = CCGetFromGet("Box_H_ID", NULL);
    }
//End Initialize Method

//SetPrimaryKeys Method @42-EBC3F86C
    function SetPrimaryKeys($PrimaryKeys) {
        $this->PrimaryKeys = $PrimaryKeys;
        return $this->PrimaryKeys;
    }
//End SetPrimaryKeys Method

//GetPrimaryKeys Method @42-74F9A772
    function GetPrimaryKeys() {
        return $this->PrimaryKeys;
    }
//End GetPrimaryKeys Method

//GetFormParameters Method @42-751C5F5D
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["PL_H_ID"][$RowNumber] = CCGetFromPost("PL_H_ID_" . $RowNumber, NULL);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber, NULL);
            $this->FormParameters["Box_H_ID"][$RowNumber] = CCGetFromPost("Box_H_ID_" . $RowNumber, NULL);
        }
    }
//End GetFormParameters Method

//Validate Method @42-0263B573
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);

        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns["PL_D_ID"] = $this->CachedColumns["PL_D_ID"][$this->RowNumber];
            $this->DataSource->CachedColumns["PL_D_ID"] = $this->CachedColumns["PL_D_ID"][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
            $this->PL_H_ID->SetText($this->FormParameters["PL_H_ID"][$this->RowNumber], $this->RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$this->RowNumber], $this->RowNumber);
            $this->Box_H_ID->SetText($this->FormParameters["Box_H_ID"][$this->RowNumber], $this->RowNumber);
            if ($this->UpdatedRows >= $this->RowNumber) {
                if(!$this->CheckBox_Delete->Value)
                    $Validation = ($this->ValidateRow() && $Validation);
            }
            else if($this->CheckInsert())
            {
                $Validation = ($this->ValidateRow() && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @42-3B6B4950
    function ValidateRow()
    {
        global $CCSLocales;
        $this->PL_H_ID->Validate();
        $this->CheckBox_Delete->Validate();
        $this->Box_H_ID->Validate();
        $this->RowErrors = new clsErrors();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidateRow", $this);
        $errors = "";
        $errors = ComposeStrings($errors, $this->PL_H_ID->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CheckBox_Delete->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Box_H_ID->Errors->ToString());
        $this->PL_H_ID->Errors->Clear();
        $this->CheckBox_Delete->Errors->Clear();
        $this->Box_H_ID->Errors->Clear();
        $errors = ComposeStrings($errors, $this->RowErrors->ToString());
        $this->RowsErrors[$this->RowNumber] = $errors;
        return $errors != "" ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @42-D21D3F80
    function CheckInsert()
    {
        $filed = false;
        $filed = ($filed || (is_array($this->FormParameters["PL_H_ID"][$this->RowNumber]) && count($this->FormParameters["PL_H_ID"][$this->RowNumber])) || strlen($this->FormParameters["PL_H_ID"][$this->RowNumber]));
        $filed = ($filed || (is_array($this->FormParameters["Box_H_ID"][$this->RowNumber]) && count($this->FormParameters["Box_H_ID"][$this->RowNumber])) || strlen($this->FormParameters["Box_H_ID"][$this->RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @42-F5A3B433
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @42-1B8C3B3B
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if($this->Button_Submit->Pressed) {
            $this->PressedButton = "Button_Submit";
        } else if($this->AddItemBtn->Pressed) {
            $this->PressedButton = "AddItemBtn";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick", $this->Button_Submit) || !$this->UpdateGrid()) {
                $Redirect = "";
            } else {
                $Redirect = "PackingList.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            }
        } else if($this->PressedButton == "AddItemBtn") {
            if(!CCGetEvent($this->AddItemBtn->CCSEvents, "OnClick", $this->AddItemBtn)) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//UpdateGrid Method @42-1D64CF46
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit", $this);
        if(!$this->Validate()) return;
        $Validation = true;
        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns["PL_D_ID"] = $this->CachedColumns["PL_D_ID"][$this->RowNumber];
            $this->DataSource->CachedColumns["PL_D_ID"] = $this->CachedColumns["PL_D_ID"][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
            $this->PL_H_ID->SetText($this->FormParameters["PL_H_ID"][$this->RowNumber], $this->RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$this->RowNumber], $this->RowNumber);
            $this->Box_H_ID->SetText($this->FormParameters["Box_H_ID"][$this->RowNumber], $this->RowNumber);
            if ($this->UpdatedRows >= $this->RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) { $Validation = ($this->DeleteRow() && $Validation); }
                } else if($this->UpdateAllowed) {
                    $Validation = ($this->UpdateRow() && $Validation);
                }
            }
            else if($this->CheckInsert() && $this->InsertAllowed)
            {
                $Validation = ($Validation && $this->InsertRow());
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit", $this);
        if ($this->Errors->Count() == 0 && $Validation){
            $this->DataSource->close();
            return true;
        }
        return false;
    }
//End UpdateGrid Method

//InsertRow Method @42-CA8161B9
    function InsertRow()
    {
        if(!$this->InsertAllowed) return false;
        $this->DataSource->PL_H_ID->SetValue($this->PL_H_ID->GetValue(true));
        $this->DataSource->RowIDAttribute->SetValue($this->RowIDAttribute->GetValue(true));
        $this->DataSource->RowStyleAttribute->SetValue($this->RowStyleAttribute->GetValue(true));
        $this->DataSource->RowNameAttribute->SetValue($this->RowNameAttribute->GetValue(true));
        $this->DataSource->Box_H_ID->SetValue($this->Box_H_ID->GetValue(true));
        $this->DataSource->AddBox->SetValue($this->AddBox->GetValue(true));
        $this->DataSource->Insert();
        $errors = "";
        if($this->DataSource->Errors->Count() > 0) {
            $errors = $this->DataSource->Errors->ToString();
            $this->RowsErrors[$this->RowNumber] = $errors;
            $this->DataSource->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @42-EED60E3F
    function UpdateRow()
    {
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->PL_H_ID->SetValue($this->PL_H_ID->GetValue(true));
        $this->DataSource->RowIDAttribute->SetValue($this->RowIDAttribute->GetValue(true));
        $this->DataSource->RowStyleAttribute->SetValue($this->RowStyleAttribute->GetValue(true));
        $this->DataSource->RowNameAttribute->SetValue($this->RowNameAttribute->GetValue(true));
        $this->DataSource->Box_H_ID->SetValue($this->Box_H_ID->GetValue(true));
        $this->DataSource->AddBox->SetValue($this->AddBox->GetValue(true));
        $this->DataSource->Update();
        $errors = "";
        if($this->DataSource->Errors->Count() > 0) {
            $errors = $this->DataSource->Errors->ToString();
            $this->RowsErrors[$this->RowNumber] = $errors;
            $this->DataSource->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @42-A4A656F6
    function DeleteRow()
    {
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $errors = "";
        if($this->DataSource->Errors->Count() > 0) {
            $errors = $this->DataSource->Errors->ToString();
            $this->RowsErrors[$this->RowNumber] = $errors;
            $this->DataSource->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @42-89E44DC2
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\n";
        $script .= "var AddNewDetailElements;\n";
        $script .= "var AddNewDetailEmptyRows = 31;\n";
        $script .= "var " . $this->ComponentName . "PL_H_IDID = 0;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 1;\n";
        $script .= "var " . $this->ComponentName . "Box_H_IDID = 2;\n";
        $script .= "\nfunction initAddNewDetailElements() {\n";
        $script .= "\tvar ED = document.forms[\"AddNewDetail\"];\n";
        $script .= "\tAddNewDetailElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.PL_H_ID_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ", " . "ED.Box_H_ID_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @42-8FD18D4C
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 2)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["PL_D_ID"][$RowNumber] = $piece;
                $piece = $pieces[$i + 1];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["PL_D_ID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["PL_D_ID"][$RowNumber] = "";
                $this->CachedColumns["PL_D_ID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @42-6AAAF911
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["PL_D_ID"][$i]));
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["PL_D_ID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @42-2B8661C4
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        global $CCSUseAmp;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->Box_H_ID->Prepare();

        $this->DataSource->open();
        $is_next_record = ($this->ReadAllowed && $this->DataSource->next_record());
        $this->IsEmpty = ! $is_next_record;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) { return; }

        $this->Attributes->Show();
        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $this->RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $this->ControlsVisible["PL_H_ID"] = $this->PL_H_ID->Visible;
        $this->ControlsVisible["CheckBox_Delete"] = $this->CheckBox_Delete->Visible;
        $this->ControlsVisible["RowIDAttribute"] = $this->RowIDAttribute->Visible;
        $this->ControlsVisible["RowStyleAttribute"] = $this->RowStyleAttribute->Visible;
        $this->ControlsVisible["RowNameAttribute"] = $this->RowNameAttribute->Visible;
        $this->ControlsVisible["Box_H_ID"] = $this->Box_H_ID->Visible;
        $this->ControlsVisible["AddBox"] = $this->AddBox->Visible;
        if ($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed)) {
            do {
                $this->RowNumber++;
                if($is_next_record) {
                    $NonEmptyRows++;
                    $this->DataSource->SetValues();
                }
                if (!($is_next_record) || !($this->DeleteAllowed)) {
                    $this->CheckBox_Delete->Visible = false;
                }
                if (!($this->FormSubmitted) && $is_next_record) {
                    $this->CachedColumns["PL_D_ID"][$this->RowNumber] = $this->DataSource->CachedColumns["PL_D_ID"];
                    $this->CachedColumns["PL_D_ID"][$this->RowNumber] = $this->DataSource->CachedColumns["PL_D_ID"];
                    $this->CheckBox_Delete->SetValue("");
                    $this->RowIDAttribute->SetText("");
                    $this->RowStyleAttribute->SetText("");
                    $this->RowNameAttribute->SetText("");
                    $this->AddBox->SetText("");
                    $this->PL_H_ID->SetValue($this->DataSource->PL_H_ID->GetValue());
                    $this->Box_H_ID->SetValue($this->DataSource->Box_H_ID->GetValue());
                } elseif ($this->FormSubmitted && $is_next_record) {
                    $this->RowIDAttribute->SetText("");
                    $this->RowStyleAttribute->SetText("");
                    $this->RowNameAttribute->SetText("");
                    $this->AddBox->SetText("");
                    $this->PL_H_ID->SetText($this->FormParameters["PL_H_ID"][$this->RowNumber], $this->RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$this->RowNumber], $this->RowNumber);
                    $this->Box_H_ID->SetText($this->FormParameters["Box_H_ID"][$this->RowNumber], $this->RowNumber);
                } elseif (!$this->FormSubmitted) {
                    $this->CachedColumns["PL_D_ID"][$this->RowNumber] = "";
                    $this->CachedColumns["PL_D_ID"][$this->RowNumber] = "";
                    $this->PL_H_ID->SetText("");
                    $this->RowIDAttribute->SetText("");
                    $this->RowStyleAttribute->SetText("");
                    $this->RowNameAttribute->SetText("");
                    $this->Box_H_ID->SetText("");
                    $this->AddBox->SetText("");
                } else {
                    $this->RowIDAttribute->SetText("");
                    $this->RowStyleAttribute->SetText("");
                    $this->RowNameAttribute->SetText("");
                    $this->AddBox->SetText("");
                    $this->PL_H_ID->SetText($this->FormParameters["PL_H_ID"][$this->RowNumber], $this->RowNumber);
                    $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$this->RowNumber], $this->RowNumber);
                    $this->Box_H_ID->SetText($this->FormParameters["Box_H_ID"][$this->RowNumber], $this->RowNumber);
                }
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->PL_H_ID->Show($this->RowNumber);
                $this->CheckBox_Delete->Show($this->RowNumber);
                $this->RowIDAttribute->Show($this->RowNumber);
                $this->RowStyleAttribute->Show($this->RowNumber);
                $this->RowNameAttribute->Show($this->RowNumber);
                $this->Box_H_ID->Show($this->RowNumber);
                $this->AddBox->Show($this->RowNumber);
                if (isset($this->RowsErrors[$this->RowNumber]) && ($this->RowsErrors[$this->RowNumber] != "")) {
                    $Tpl->setblockvar("RowError", "");
                    $Tpl->setvar("Error", $this->RowsErrors[$this->RowNumber]);
                    $this->Attributes->Show();
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($this->RowNumber));
                $Tpl->parse();
                if ($is_next_record) {
                    if ($this->FormSubmitted) {
                        $is_next_record = $this->RowNumber < $this->UpdatedRows;
                        if (($this->DataSource->CachedColumns["PL_D_ID"] == $this->CachedColumns["PL_D_ID"][$this->RowNumber]) && ($this->DataSource->CachedColumns["PL_D_ID"] == $this->CachedColumns["PL_D_ID"][$this->RowNumber])) {
                            if ($this->ReadAllowed) $this->DataSource->next_record();
                        }
                    }else{
                        $is_next_record = ($this->RowNumber < $this->PageSize) &&  $this->ReadAllowed && $this->DataSource->next_record();
                    }
                } else { 
                    $EmptyRowsLeft--;
                }
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Button_Submit->Show();
        $this->AddItemBtn->Show();

        if($this->CheckErrors()) {
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        if (!$CCSUseAmp) {
            $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        } else {
            $Tpl->SetVar("HTMLFormProperties", "method=\"post\" action=\"" . str_replace("&", "&amp;", $this->HTMLFormAction) . "\" id=\"" . $this->ComponentName . "\"");
        }
        $Tpl->SetVar("FormState", CCToHTML($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End AddNewDetail Class @42-FCB6E20C

class clsAddNewDetailDataSource extends clsDBgayafusionall {  //AddNewDetailDataSource Class @42-9729D968

//DataSource Variables @42-EE695258
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $DeleteParameters;
    public $CountSQL;
    public $wp;
    public $AllParametersSet;

    public $CachedColumns;
    public $CurrentRow;
    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $PL_H_ID;
    public $CheckBox_Delete;
    public $RowIDAttribute;
    public $RowStyleAttribute;
    public $RowNameAttribute;
    public $Box_H_ID;
    public $AddBox;
//End DataSource Variables

//DataSourceClass_Initialize Event @42-9AADE26F
    function clsAddNewDetailDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "EditableGrid AddNewDetail/Error";
        $this->Initialize();
        $this->PL_H_ID = new clsField("PL_H_ID", ccsInteger, "");
        
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, $this->BooleanFormat);
        
        $this->RowIDAttribute = new clsField("RowIDAttribute", ccsText, "");
        
        $this->RowStyleAttribute = new clsField("RowStyleAttribute", ccsText, "");
        
        $this->RowNameAttribute = new clsField("RowNameAttribute", ccsText, "");
        
        $this->Box_H_ID = new clsField("Box_H_ID", ccsInteger, "");
        
        $this->AddBox = new clsField("AddBox", ccsText, "");
        

        $this->InsertFields["PL_H_ID"] = array("Name" => "PL_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["Box_H_ID"] = array("Name" => "Box_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["PL_H_ID"] = array("Name" => "PL_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Box_H_ID"] = array("Name" => "Box_H_ID", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//SetOrder Method @42-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @42-22037A77
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlPL_H_ID", ccsInteger, "", "", $this->Parameters["urlPL_H_ID"], "", false);
        $this->wp->AddParameter("2", "urlBox_H_ID", ccsInteger, "", "", $this->Parameters["urlBox_H_ID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "PL_H_ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "Box_H_ID", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @42-FC92859F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM tbladminist_packinglist_d";
        $this->SQL = "SELECT * \n\n" .
        "FROM tbladminist_packinglist_d {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @42-52A824B2
    function SetValues()
    {
        $this->CachedColumns["PL_D_ID"] = $this->f("PL_D_ID");
        $this->CachedColumns["PL_D_ID"] = $this->f("PL_D_ID");
        $this->PL_H_ID->SetDBValue(trim($this->f("PL_H_ID")));
        $this->Box_H_ID->SetDBValue(trim($this->f("Box_H_ID")));
    }
//End SetValues Method

//Insert Method @42-EC51CAB1
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["PL_H_ID"]["Value"] = $this->PL_H_ID->GetDBValue(true);
        $this->InsertFields["Box_H_ID"]["Value"] = $this->Box_H_ID->GetDBValue(true);
        $this->SQL = CCBuildInsert("tbladminist_packinglist_d", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @42-949C5C39
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $SelectWhere = $this->Where;
        $this->Where = "PL_D_ID=" . $this->ToSQL($this->CachedColumns["PL_D_ID"], ccsInteger) . " AND PL_D_ID=" . $this->ToSQL($this->CachedColumns["PL_D_ID"], ccsInteger);
        $this->UpdateFields["PL_H_ID"]["Value"] = $this->PL_H_ID->GetDBValue(true);
        $this->UpdateFields["Box_H_ID"]["Value"] = $this->Box_H_ID->GetDBValue(true);
        $this->SQL = CCBuildUpdate("tbladminist_packinglist_d", $this->UpdateFields, $this);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
        $this->Where = $SelectWhere;
    }
//End Update Method

//Delete Method @42-A6D66BB5
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $SelectWhere = $this->Where;
        $this->Where = "PL_D_ID=" . $this->ToSQL($this->CachedColumns["PL_D_ID"], ccsInteger) . " AND PL_D_ID=" . $this->ToSQL($this->CachedColumns["PL_D_ID"], ccsInteger);
        $this->SQL = "DELETE FROM tbladminist_packinglist_d";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
        $this->Where = $SelectWhere;
    }
//End Delete Method

} //End AddNewDetailDataSource Class @42-FCB6E20C

//Initialize Page @1-D7A13716
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "AddPackList.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-3784713C
include_once("./AddPackList_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-D7B2DEB3
$DBgayafusionall = new clsDBgayafusionall();
$MainPage->Connections["gayafusionall"] = & $DBgayafusionall;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$AddNewHeader = new clsRecordAddNewHeader("", $MainPage);
$AddNewDetail = new clsEditableGridAddNewDetail("", $MainPage);
$MainPage->AddNewHeader = & $AddNewHeader;
$MainPage->AddNewDetail = & $AddNewDetail;
$AddNewHeader->Initialize();
$AddNewDetail->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-E710DB26
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-5A7F0812
$AddNewHeader->Operation();
$AddNewDetail->Operation();
//End Execute Components

//Go to destination page @1-D72800B7
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBgayafusionall->close();
    header("Location: " . $Redirect);
    unset($AddNewHeader);
    unset($AddNewDetail);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-2594D923
$AddNewHeader->Show();
$AddNewDetail->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-142ABB44
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBgayafusionall->close();
unset($AddNewHeader);
unset($AddNewDetail);
unset($Tpl);
//End Unload Page


?>
