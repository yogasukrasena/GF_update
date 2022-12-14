<?php
//Include Common Files @1-91A5F0AF
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "Reference.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridtblreference { //tblreference class @2-937D14F7

//Variables @2-101F15AC

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
    public $Sorter_RefCode;
    public $Sorter_sID;
    public $Sorter_CollectID;
//End Variables

//Class_Initialize Event @2-961BB661
    function clsGridtblreference($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tblreference";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tblreference";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clstblreferenceDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("tblreferenceOrder", "");
        $this->SorterDirection = CCGetParam("tblreferenceDir", "");

        $this->RefCode = new clsControl(ccsLink, "RefCode", "RefCode", ccsText, "", CCGetRequestParam("RefCode", ccsGet, NULL), $this);
        $this->RefCode->Page = "Reference.php";
        $this->sID = new clsControl(ccsHidden, "sID", "sID", ccsInteger, "", CCGetRequestParam("sID", ccsGet, NULL), $this);
        $this->CollectID = new clsControl(ccsHidden, "CollectID", "CollectID", ccsInteger, "", CCGetRequestParam("CollectID", ccsGet, NULL), $this);
        $this->RefNote = new clsControl(ccsLabel, "RefNote", "RefNote", ccsMemo, "", CCGetRequestParam("RefNote", ccsGet, NULL), $this);
        $this->SampleCode = new clsControl(ccsLabel, "SampleCode", "SampleCode", ccsText, "", CCGetRequestParam("SampleCode", ccsGet, NULL), $this);
        $this->CollectCode = new clsControl(ccsLabel, "CollectCode", "CollectCode", ccsText, "", CCGetRequestParam("CollectCode", ccsGet, NULL), $this);
        $this->tblreference_TotalRecords = new clsControl(ccsLabel, "tblreference_TotalRecords", "tblreference_TotalRecords", ccsText, "", CCGetRequestParam("tblreference_TotalRecords", ccsGet, NULL), $this);
        $this->Sorter_RefCode = new clsSorter($this->ComponentName, "Sorter_RefCode", $FileName, $this);
        $this->Sorter_sID = new clsSorter($this->ComponentName, "Sorter_sID", $FileName, $this);
        $this->Sorter_CollectID = new clsSorter($this->ComponentName, "Sorter_CollectID", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-5E80501C
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlCollectID"] = CCGetFromGet("CollectID", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["RefCode"] = $this->RefCode->Visible;
            $this->ControlsVisible["sID"] = $this->sID->Visible;
            $this->ControlsVisible["CollectID"] = $this->CollectID->Visible;
            $this->ControlsVisible["RefNote"] = $this->RefNote->Visible;
            $this->ControlsVisible["SampleCode"] = $this->SampleCode->Visible;
            $this->ControlsVisible["CollectCode"] = $this->CollectCode->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->RefCode->SetValue($this->DataSource->RefCode->GetValue());
                $this->RefCode->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->RefCode->Parameters = CCAddParam($this->RefCode->Parameters, "ID", $this->DataSource->f("ID"));
                $this->sID->SetValue($this->DataSource->sID->GetValue());
                $this->CollectID->SetValue($this->DataSource->CollectID->GetValue());
                $this->RefNote->SetValue($this->DataSource->RefNote->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->RefCode->Show();
                $this->sID->Show();
                $this->CollectID->Show();
                $this->RefNote->Show();
                $this->SampleCode->Show();
                $this->CollectCode->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->Navigator->TotalPages <= 1) {
            $this->Navigator->Visible = false;
        }
        $this->tblreference_TotalRecords->Show();
        $this->Sorter_RefCode->Show();
        $this->Sorter_sID->Show();
        $this->Sorter_CollectID->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-322AC809
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->RefCode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->sID->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CollectID->Errors->ToString());
        $errors = ComposeStrings($errors, $this->RefNote->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SampleCode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CollectCode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tblreference Class @2-FCB6E20C

class clstblreferenceDataSource extends clsDBgayafusionall {  //tblreferenceDataSource Class @2-2551639C

//DataSource Variables @2-CF9642CB
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $RefCode;
    public $sID;
    public $CollectID;
    public $RefNote;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-41260664
    function clstblreferenceDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tblreference";
        $this->Initialize();
        $this->RefCode = new clsField("RefCode", ccsText, "");
        
        $this->sID = new clsField("sID", ccsInteger, "");
        
        $this->CollectID = new clsField("CollectID", ccsInteger, "");
        
        $this->RefNote = new clsField("RefNote", ccsMemo, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-4B069D18
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "CollectID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_RefCode" => array("RefCode", ""), 
            "Sorter_sID" => array("sID", ""), 
            "Sorter_CollectID" => array("CollectID", "")));
    }
//End SetOrder Method

//Prepare Method @2-8E14086C
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCollectID", ccsInteger, "", "", $this->Parameters["urlCollectID"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "CollectID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-EEC567AB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM tblreference";
        $this->SQL = "SELECT * \n\n" .
        "FROM tblreference {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-3A9B9258
    function SetValues()
    {
        $this->RefCode->SetDBValue($this->f("RefCode"));
        $this->sID->SetDBValue(trim($this->f("sID")));
        $this->CollectID->SetDBValue(trim($this->f("CollectID")));
        $this->RefNote->SetDBValue($this->f("RefNote"));
    }
//End SetValues Method

} //End tblreferenceDataSource Class @2-FCB6E20C

class clsRecordRecord { //Record Class @15-F67D2280

//Variables @15-9E315808

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

//Class_Initialize Event @15-53F7064C
    function clsRecordRecord($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record Record/Error";
        $this->DataSource = new clsRecordDataSource($this);
        $this->ds = & $this->DataSource;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "Record";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->RefNote = new clsControl(ccsTextArea, "RefNote", "Ref Note", ccsMemo, "", CCGetRequestParam("RefNote", $Method, NULL), $this);
            $this->CollectID = new clsControl(ccsHidden, "CollectID", "CollectID", ccsText, "", CCGetRequestParam("CollectID", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @15-D6CB1C94
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlID"] = CCGetFromGet("ID", NULL);
    }
//End Initialize Method

//Validate Method @15-5A844667
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->RefNote->Validate() && $Validation);
        $Validation = ($this->CollectID->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->RefNote->Errors->Count() == 0);
        $Validation =  $Validation && ($this->CollectID->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @15-C4A61F46
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->RefNote->Errors->Count());
        $errors = ($errors || $this->CollectID->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @15-ED598703
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

//Operation Method @15-5D1101C0
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
            $this->PressedButton = $this->EditMode ? "Button_Update" : "";
            if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_Update") {
                $Redirect = "Collection.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//UpdateRow Method @15-7749E3E0
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->RefNote->SetValue($this->RefNote->GetValue(true));
        $this->DataSource->CollectID->SetValue($this->CollectID->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @15-7BE97C80
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
                    $this->RefNote->SetValue($this->DataSource->RefNote->GetValue());
                    $this->CollectID->SetValue($this->DataSource->CollectID->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->RefNote->Errors->ToString());
            $Error = ComposeStrings($Error, $this->CollectID->Errors->ToString());
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
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Update->Show();
        $this->RefNote->Show();
        $this->CollectID->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End Record Class @15-FCB6E20C

class clsRecordDataSource extends clsDBgayafusionall {  //RecordDataSource Class @15-12387622

//DataSource Variables @15-9825EFAA
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $UpdateParameters;
    public $wp;
    public $AllParametersSet;

    public $UpdateFields = array();

    // Datasource fields
    public $RefNote;
    public $CollectID;
//End DataSource Variables

//DataSourceClass_Initialize Event @15-537F29B5
    function clsRecordDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record Record/Error";
        $this->Initialize();
        $this->RefNote = new clsField("RefNote", ccsMemo, "");
        
        $this->CollectID = new clsField("CollectID", ccsText, "");
        

        $this->UpdateFields["RefNote"] = array("Name" => "RefNote", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->UpdateFields["CollectID"] = array("Name" => "CollectID", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @15-C6736E1B
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlID", ccsInteger, "", "", $this->Parameters["urlID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "ID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @15-0103AD7D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM tblreference {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @15-11BC32B8
    function SetValues()
    {
        $this->RefNote->SetDBValue($this->f("RefNote"));
        $this->CollectID->SetDBValue($this->f("CollectID"));
    }
//End SetValues Method

//Update Method @15-DC433DB8
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["RefNote"]["Value"] = $this->RefNote->GetDBValue(true);
        $this->UpdateFields["CollectID"]["Value"] = $this->CollectID->GetDBValue(true);
        $this->SQL = CCBuildUpdate("tblreference", $this->UpdateFields, $this);
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

} //End RecordDataSource Class @15-FCB6E20C

//Initialize Page @1-02E74508
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
$TemplateFileName = "Reference.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-9811F46B
include_once("./Reference_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-88C68125
$DBgayafusionall = new clsDBgayafusionall();
$MainPage->Connections["gayafusionall"] = & $DBgayafusionall;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$tblreference = new clsGridtblreference("", $MainPage);
$Record = new clsRecordRecord("", $MainPage);
$MainPage->tblreference = & $tblreference;
$MainPage->Record = & $Record;
$tblreference->Initialize();
$Record->Initialize();

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

//Execute Components @1-5C37F57F
$Record->Operation();
//End Execute Components

//Go to destination page @1-9351D1CA
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBgayafusionall->close();
    header("Location: " . $Redirect);
    unset($tblreference);
    unset($Record);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-A23B69A4
$tblreference->Show();
$Record->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-F9C9E102
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBgayafusionall->close();
unset($tblreference);
unset($Record);
unset($Tpl);
//End Unload Page


?>
