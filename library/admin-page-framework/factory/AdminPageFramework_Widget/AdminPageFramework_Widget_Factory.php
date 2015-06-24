<?php
/**
 Admin Page Framework v3.5.9 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_Widget_Factory extends WP_Widget {
    public function __construct($oCaller, $sWidgetTitle, array $aArguments = array()) {
        $aArguments = $aArguments + array('classname' => 'admin_page_framework_widget', 'description' => '',);
        parent::__construct($oCaller->oProp->sClassName, $sWidgetTitle, $aArguments);
        $this->oCaller = $oCaller;
    }
    public function widget($aArguments, $aFormData) {
        echo $aArguments['before_widget'];
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, 'do_' . $this->oCaller->oProp->sClassName, $this->oCaller);
        $_sContent = $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "content_{$this->oCaller->oProp->sClassName}", $this->oCaller->content('', $aArguments, $aFormData), $aArguments, $aFormData);
        echo $this->_getTitle($aArguments, $aFormData);
        echo $_sContent;
        echo $aArguments['after_widget'];
    }
    private function _getTitle(array $aArguments, array $aFormData) {
        if (!$this->oCaller->oProp->bShowWidgetTitle) {
            return '';
        }
        $_sTitle = apply_filters('widget_title', $this->oCaller->oUtil->getElement($aFormData, 'title', ''), $aFormData, $this->id_base);
        if (!$_sTitle) {
            return '';
        }
        return $aArguments['before_title'] . $_sTitle . $aArguments['after_title'];
    }
    public function update($aSubmittedFormData, $aSavedFormData) {
        return $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "validation_{$this->oCaller->oProp->sClassName}", call_user_func_array(array($this->oCaller, 'validate'), array($aSubmittedFormData, $aSavedFormData, $this->oCaller)), $aSavedFormData, $this->oCaller);
    }
    public function form($aFormData) {
        $this->oCaller->load($this->oCaller);
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, 'load_' . $this->oCaller->oProp->sClassName, $this->oCaller);
        $this->oCaller->_registerFormElements($aFormData);
        $this->oCaller->oProp->aFieldCallbacks = array('hfID' => array($this, 'get_field_id'), 'hfTagID' => array($this, 'get_field_id'), 'hfName' => array($this, '_replyToGetInputName'), 'hfNameFlat' => array($this, '_replyToGetFlatInputName'),);
        $this->oCaller->_printWidgetForm();
        $this->oCaller->oForm = new AdminPageFramework_FormElement($this->oCaller->oProp->sFieldsType, $this->oCaller->oProp->sCapability, $this->oCaller);
    }
    public function _replyToGetInputName() {
        $_aParams = func_get_args() + array(null, null, null);
        $aField = $_aParams[1];
        $sKey = ( string )$_aParams[2];
        $_sKey = $this->oCaller->oUtil->getAOrB('0' !== $sKey && empty($sKey), '', "[{$sKey}]");
        $_sSectionIndex = isset($aField['section_id'], $aField['_section_index']) ? "[{$aField['_section_index']}]" : "";
        $_sID = $this->oCaller->isSectionSet($aField) ? "{$aField['section_id']}]{$_sSectionIndex}[{$aField['field_id']}" : "{$aField['field_id']}";
        return $this->get_field_name($_sID) . $_sKey;
    }
    protected function _replyToGetFlatInputName() {
        $_aParams = func_get_args() + array(null, null, null);
        $aField = $_aParams[1];
        $sKey = ( string )$_aParams[2];
        $_sKey = $this->oCaller->oUtil->getAOrB('0' !== $sKey && empty($sKey), '', "|{$_sKey}");
        $_sSectionIndex = isset($aField['section_id'], $aField['_section_index']) ? "|{$aField['_section_index']}" : '';
        $sFlatInputName = $this->oCaller->isSectionSet($aField) ? "{$aField['section_id']}{$_sSectionIndex}|{$aField['field_id']}" : "{$aField['field_id']}";
        return $sFlatInputName . $_sKey;
    }
}