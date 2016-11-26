<?php
// Heading
$_['heading_title']                         = 'Export / Import';

// Text
$_['text_success']                          = 'Success: You have successfully imported your data!';
$_['text_success_settings']                 = 'Success: You have successfully updated the settings for the Export/Import tool!';
$_['text_export_type_category']             = 'Categories';
$_['text_export_type_instrument']              = 'Instruments (including instrument data, options, specials, discounts, rewards and attributes)';
$_['text_export_type_option']               = 'Option definitions';
$_['text_export_type_attribute']            = 'Attribute definitions';
$_['text_yes']                              = 'Yes';
$_['text_no']                               = 'No';
$_['text_nochange']                         = 'No server data has been changed.';
$_['text_log_details']                      = 'See also \'System &gt; Error Logs\' for more details.';
$_['text_loading_notifications']            = 'Getting messages';
$_['text_retry']                            = 'Retry';

// Entry
$_['entry_import']                          = 'Import from a XLS, XLSX or ODS spreadsheet file';
$_['entry_export']                          = 'Export requested data to a XLSX spreadsheet file.';
$_['entry_export_type']                     = 'Select what data you want to export:';
$_['entry_range_type']                      = 'Please select the data range you want to export:';
$_['entry_start_id']                        = 'Start id:';
$_['entry_start_index']                     = 'Counts per batch:';
$_['entry_end_id']                          = 'End id:';
$_['entry_end_index']                       = 'The batch number:';
$_['entry_incremental']                     = 'Use incremental Import';
$_['entry_upload']                          = 'File to be uploaded';
$_['entry_settings_use_option_id']          = 'Use <em>option_id</em> instead of <em>option name</em> in worksheets \'InstrumentOptions\' and \'InstrumentOptionValues\'';
$_['entry_settings_use_option_value_id']    = 'Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet \'InstrumentOptionValues\'';
$_['entry_settings_use_attribute_group_id'] = 'Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheets \'InstrumentAttributes\'';
$_['entry_settings_use_attribute_id']       = 'Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet \'InstrumentAttributes\'';
$_['entry_settings_use_export_cache']       = 'Use phpTemp cache for large Exports (will be slightly slower)';
$_['entry_settings_use_import_cache']       = 'Use phpTemp cache for large Imports (will be slightly slower)';

// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify Export/Import!';
$_['error_upload']                          = 'Uploaded file is not a valid spreadsheet file or its values are not in the expected formats!';
$_['error_categories_header']               = 'Export/Import: Invalid header in the Categories worksheet';
$_['error_instruments_header']                 = 'Export/Import: Invalid header in the Instruments worksheet';
$_['error_additional_images_header']        = 'Export/Import: Invalid header in the AdditionalImages worksheet';
$_['error_specials_header']                 = 'Export/Import: Invalid header in the Specials worksheet';
$_['error_discounts_header']                = 'Export/Import: Invalid header in the Discounts worksheet';
$_['error_rewards_header']                  = 'Export/Import: Invalid header in the Rewards worksheet';
$_['error_instrument_options_header']          = 'Export/Import: Invalid header in the InstrumentOptions worksheet';
$_['error_instrument_option_values_header']    = 'Export/Import: Invalid header in the InstrumentOptionValues worksheet';
$_['error_options_header']                  = 'Export/Import: Invalid header in the Options worksheet';
$_['error_option_values_header']            = 'Export/Import: Invalid header in the OptionValues worksheet';
$_['error_attribute_groups_header']         = 'Export/Import: Invalid header in the AttributeGroups worksheet';
$_['error_attributes_header']               = 'Export/Import: Invalid header in the Attributes worksheet';
$_['error_instrument_options']                 = 'Missing Instruments worksheet, or Instruments worksheet not listed before InstrumentOptions';
$_['error_instrument_option_values']           = 'Missing Instruments worksheet, or Instruments worksheet not listed before InstrumentOptionValues';
$_['error_instrument_option_values_2']         = 'Missing InstrumentOptions worksheet, or InstrumentOptions worksheet not listed before InstrumentOptionValues';
$_['error_instrument_option_values_3']         = 'InstrumentOptionValues worksheet also expected after a InstrumentOptions worksheet';
$_['error_additional_images']               = 'Missing Instruments worksheet, or Instruments worksheet not listed before AdditionalImages';
$_['error_specials']                        = 'Missing Instruments worksheet, or Instruments worksheet not listed before Specials';
$_['error_discounts']                       = 'Missing Instruments worksheet, or Instruments worksheet not listed before Discounts';
$_['error_rewards']                         = 'Missing Instruments worksheet, or Instruments worksheet not listed before Rewards';
$_['error_instrument_attributes']              = 'Missing Instruments worksheet, or Instruments worksheet not listed before InstrumentAttributes';
$_['error_attributes']                      = 'Missing AttributeGroups worksheet, or AttributeGroups worksheet not listed before Attributes';
$_['error_attributes_2']                    = 'Attributes worksheet also expected after an AttributeGroups worksheet';
$_['error_option_values']                   = 'Missing Options worksheet, or Options worksheet not listed before OptionValues';
$_['error_option_values_2']                 = 'OptionValues worksheet also expected after an Options worksheet';
$_['error_post_max_size']                   = 'Export/Import: File size is greater than %1 (see PHP setting \'post_max_size\')';
$_['error_upload_max_filesize']             = 'Export/Import: File size is greater than %1 (see PHP setting \'upload_max_filesize\')';
$_['error_select_file']                     = 'Export/Import: Please select a file before clicking \'Import\'';
$_['error_id_no_data']                      = 'No data between start-id and end-id.';
$_['error_page_no_data']                    = 'No more data.';
$_['error_param_not_number']                = 'Values for data range must be whole numbers.';
$_['error_upload_name']                     = 'Missing file name for upload';
$_['error_upload_ext']                      = 'Uploaded file has not one of the \'.xls\', \'.xlsx\' or \'.ods\' file name extensions, it might not be a spreadsheet file!';
$_['error_notifications']                   = 'Could not load messages from MHCCORP.COM.';
$_['error_no_news']                         = 'No messages';
$_['error_batch_number']                    = 'Batch number must be greater than 0';
$_['error_min_item_id']                     = 'Start id must be greater than 0';
$_['error_option_name']                     = 'Option \'%1\' is defined multiple times!<br />';
$_['error_option_name']                    .= 'In the Settings-tab please activate the following:<br />';
$_['error_option_name']                    .= "Use <em>option_id</em> instead of <em>option name</em> in worksheets 'InstrumentOptions' and 'InstrumentOptionValues'";
$_['error_option_value_name']               = 'Option value \'%1\' is defined multiple times within its option!<br />';
$_['error_option_value_name']              .= 'In the Settings-tab please activate the following:<br />';
$_['error_option_value_name']              .= "Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet 'InstrumentOptionValues'";
$_['error_attribute_group_name']            = 'AttributeGroup \'%1\' is defined multiple times!<br />';
$_['error_attribute_group_name']           .= 'In the Settings-tab please activate the following:<br />';
$_['error_attribute_group_name']           .= "Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheets 'InstrumentAttributes'";
$_['error_attribute_name']                  = 'Attribute \'%1\' is defined multiple times within its attribute group!<br />';
$_['error_attribute_name']                 .= 'In the Settings-tab please activate the following:<br />';
$_['error_attribute_name']                 .= "Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet 'InstrumentAttributes'";

// Tabs
$_['tab_import']                            = 'Import';
$_['tab_export']                            = 'Export';
$_['tab_settings']                          = 'Settings';

// Button labels
$_['button_import']                         = 'Import';
$_['button_export']                         = 'Export';
$_['button_settings']                       = 'Update Settings';
$_['button_export_id']                      = 'By id range';
$_['button_export_page']                    = 'By batches';

// Help
$_['help_range_type']                       = '(Optional, leave empty if not needed)';
$_['help_incremental_yes']                  = '(Update and/or add data)';
$_['help_incremental_no']                   = '(Delete all old data before Import)';
$_['help_import']                           = 'Spreadsheet can have categories, instruments, attribute definitions or option definitions. ';
$_['help_format']                           = 'Do an Export first to see the exact format of the worksheets!';
?>