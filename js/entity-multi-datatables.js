
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

$(document).ready(function (){


    var baseUrl = shadowcore_base_url.baseUrl;
   // Array holding selected row IDs
   var sb_currency_rows_selected = [];
   var sb_currencyTable =  $('#sb_currency-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_currency-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "symbol" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="currency_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#currency_parent_params').length) {
                        parent_params = parent_params + $('#currency_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=currency&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="currency" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_currency_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_currency-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_currencyTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_currency_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_currency_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_currency_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_currencyTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_currency-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_currencyTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_currency-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_currency-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_currencyTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_currencyTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-currency-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_currency_rows_selected, function(index, rowId){

        $.each($('input[name="currency_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'currency_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#currency_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#currency_dependent_list_box').append($(
                '<div id="currency_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="currency" ' + 
                        'data-entity-id="' + rowId + '" class="badge currency_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#currency_dependent_list_box').on('click', '.currency_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="currency_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#currency_dependent_list_box').find('#currency_list_item_' + entityId).remove();

      
   });
   var sb_loctype_rows_selected = [];
   var sb_loctypeTable =  $('#sb_loctype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_loctype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="locationtype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#locationtype_parent_params').length) {
                        parent_params = parent_params + $('#locationtype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=locationtype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="locationtype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_loctype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_loctype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_loctypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_loctype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_loctype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_loctype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_loctypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_loctype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_loctypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_loctype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_loctype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_loctypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_loctypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-locationtype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_loctype_rows_selected, function(index, rowId){

        $.each($('input[name="locationtype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'locationtype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#locationtype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#locationtype_dependent_list_box').append($(
                '<div id="locationtype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="locationtype" ' + 
                        'data-entity-id="' + rowId + '" class="badge locationtype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#locationtype_dependent_list_box').on('click', '.locationtype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="locationtype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#locationtype_dependent_list_box').find('#locationtype_list_item_' + entityId).remove();

      
   });
   var sb_location_rows_selected = [];
   var sb_locationTable =  $('#sb_location-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_location-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },


            { data: "location_type_txt" },

            { data: "location_txt" },
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="location_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#location_parent_params').length) {
                        parent_params = parent_params + $('#location_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=location&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="location" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_location_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_location-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_locationTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_location_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_location_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_location_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_locationTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_location-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_locationTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_location-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_location-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_locationTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_locationTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-location-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_location_rows_selected, function(index, rowId){

        $.each($('input[name="location_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'location_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#location_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#location_dependent_list_box').append($(
                '<div id="location_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="location" ' + 
                        'data-entity-id="' + rowId + '" class="badge location_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#location_dependent_list_box').on('click', '.location_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="location_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#location_dependent_list_box').find('#location_list_item_' + entityId).remove();

      
   });
   var sb_business_rows_selected = [];
   var sb_businessTable =  $('#sb_business-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_business-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },


            { data: "currency_txt" },
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="business_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#business_parent_params').length) {
                        parent_params = parent_params + $('#business_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=business&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="business" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_business_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_business-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_businessTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_business_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_business_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_business_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_businessTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_business-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_businessTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_business-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_business-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_businessTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_businessTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-business-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_business_rows_selected, function(index, rowId){

        $.each($('input[name="business_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'business_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#business_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#business_dependent_list_box').append($(
                '<div id="business_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="business" ' + 
                        'data-entity-id="' + rowId + '" class="badge business_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#business_dependent_list_box').on('click', '.business_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="business_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#business_dependent_list_box').find('#business_list_item_' + entityId).remove();

      
   });
   var sb_businessunit_rows_selected = [];
   var sb_businessunitTable =  $('#sb_businessunit-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_businessunit-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "currency_txt" },
            { data: "name" },

            { data: "address_1" },

            { data: "address_2" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="businessunit_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#businessunit_parent_params').length) {
                        parent_params = parent_params + $('#businessunit_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=businessunit&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="businessunit" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_businessunit_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_businessunit-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_businessunitTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_businessunit_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_businessunit_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_businessunit_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_businessunitTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_businessunit-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_businessunitTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_businessunit-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_businessunit-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_businessunitTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_businessunitTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-businessunit-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_businessunit_rows_selected, function(index, rowId){

        $.each($('input[name="businessunit_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'businessunit_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#businessunit_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#businessunit_dependent_list_box').append($(
                '<div id="businessunit_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="businessunit" ' + 
                        'data-entity-id="' + rowId + '" class="badge businessunit_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#businessunit_dependent_list_box').on('click', '.businessunit_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="businessunit_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#businessunit_dependent_list_box').find('#businessunit_list_item_' + entityId).remove();

      
   });
   var sb_partycat_rows_selected = [];
   var sb_partycatTable =  $('#sb_partycat-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partycat-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partycategory_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partycategory_parent_params').length) {
                        parent_params = parent_params + $('#partycategory_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partycategory&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partycategory" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partycat_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partycat-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partycatTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partycat_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partycat_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partycat_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partycatTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partycat-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partycatTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partycat-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partycat-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partycatTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partycatTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partycategory-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partycat_rows_selected, function(index, rowId){

        $.each($('input[name="partycategory_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partycategory_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partycategory_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partycategory_dependent_list_box').append($(
                '<div id="partycategory_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partycategory" ' + 
                        'data-entity-id="' + rowId + '" class="badge partycategory_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partycategory_dependent_list_box').on('click', '.partycategory_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partycategory_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partycategory_dependent_list_box').find('#partycategory_list_item_' + entityId).remove();

      
   });
   var sb_partytype_rows_selected = [];
   var sb_partytypeTable =  $('#sb_partytype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partytype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "party_category_txt" },
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partytype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partytype_parent_params').length) {
                        parent_params = parent_params + $('#partytype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partytype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partytype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partytype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partytype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partytypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partytype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partytype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partytype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partytypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partytype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partytypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partytype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partytype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partytypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partytypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partytype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partytype_rows_selected, function(index, rowId){

        $.each($('input[name="partytype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partytype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partytype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partytype_dependent_list_box').append($(
                '<div id="partytype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partytype" ' + 
                        'data-entity-id="' + rowId + '" class="badge partytype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partytype_dependent_list_box').on('click', '.partytype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partytype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partytype_dependent_list_box').find('#partytype_list_item_' + entityId).remove();

      
   });
   var sb_roletype_rows_selected = [];
   var sb_roletypeTable =  $('#sb_roletype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_roletype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="roletype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#roletype_parent_params').length) {
                        parent_params = parent_params + $('#roletype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=roletype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="roletype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_roletype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_roletype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_roletypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_roletype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_roletype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_roletype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_roletypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_roletype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_roletypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_roletype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_roletype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_roletypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_roletypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-roletype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_roletype_rows_selected, function(index, rowId){

        $.each($('input[name="roletype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'roletype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#roletype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#roletype_dependent_list_box').append($(
                '<div id="roletype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="roletype" ' + 
                        'data-entity-id="' + rowId + '" class="badge roletype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#roletype_dependent_list_box').on('click', '.roletype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="roletype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#roletype_dependent_list_box').find('#roletype_list_item_' + entityId).remove();

      
   });
   var sb_party_rows_selected = [];
   var sb_partyTable =  $('#sb_party-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_party-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "party_type_txt" },
            { data: "name" },

            { data: "description" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="party_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#party_parent_params').length) {
                        parent_params = parent_params + $('#party_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=party&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="party" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_party_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_party-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partyTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_party_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_party_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_party_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_party-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partyTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_party-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_party-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partyTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-party-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_party_rows_selected, function(index, rowId){

        $.each($('input[name="party_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'party_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#party_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#party_dependent_list_box').append($(
                '<div id="party_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="party" ' + 
                        'data-entity-id="' + rowId + '" class="badge party_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#party_dependent_list_box').on('click', '.party_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="party_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#party_dependent_list_box').find('#party_list_item_' + entityId).remove();

      
   });
   var sb_partyrole_rows_selected = [];
   var sb_partyroleTable =  $('#sb_partyrole-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partyrole-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "party_txt" },

            { data: "parent_prole_txt" },

            { data: "role_txt" },

            { data: "parent_unit_txt" },
            { data: "name" },

            { data: "description" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partyrole_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyrole_parent_params').length) {
                        parent_params = parent_params + $('#partyrole_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partyrole&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyrole" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partyrole_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partyrole-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partyroleTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partyrole_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partyrole_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partyrole_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyroleTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partyrole-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partyroleTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partyrole-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partyrole-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partyroleTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyroleTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partyrole-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partyrole_rows_selected, function(index, rowId){

        $.each($('input[name="partyrole_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partyrole_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partyrole_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partyrole_dependent_list_box').append($(
                '<div id="partyrole_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partyrole" ' + 
                        'data-entity-id="' + rowId + '" class="badge partyrole_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partyrole_dependent_list_box').on('click', '.partyrole_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partyrole_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partyrole_dependent_list_box').find('#partyrole_list_item_' + entityId).remove();

      
   });
   var sb_reltype_rows_selected = [];
   var sb_reltypeTable =  $('#sb_reltype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_reltype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="relationshiptype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#relationshiptype_parent_params').length) {
                        parent_params = parent_params + $('#relationshiptype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=relationshiptype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="relationshiptype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_reltype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_reltype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_reltypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_reltype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_reltype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_reltype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_reltypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_reltype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_reltypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_reltype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_reltype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_reltypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_reltypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-relationshiptype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_reltype_rows_selected, function(index, rowId){

        $.each($('input[name="relationshiptype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'relationshiptype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#relationshiptype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#relationshiptype_dependent_list_box').append($(
                '<div id="relationshiptype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="relationshiptype" ' + 
                        'data-entity-id="' + rowId + '" class="badge relationshiptype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#relationshiptype_dependent_list_box').on('click', '.relationshiptype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="relationshiptype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#relationshiptype_dependent_list_box').find('#relationshiptype_list_item_' + entityId).remove();

      
   });
   var sb_relstatus_rows_selected = [];
   var sb_relstatusTable =  $('#sb_relstatus-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_relstatus-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="relationshipstatus_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#relationshipstatus_parent_params').length) {
                        parent_params = parent_params + $('#relationshipstatus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=relationshipstatus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="relationshipstatus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_relstatus_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_relstatus-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_relstatusTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_relstatus_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_relstatus_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_relstatus_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_relstatusTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_relstatus-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_relstatusTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_relstatus-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_relstatus-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_relstatusTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_relstatusTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-relationshipstatus-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_relstatus_rows_selected, function(index, rowId){

        $.each($('input[name="relationshipstatus_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'relationshipstatus_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#relationshipstatus_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#relationshipstatus_dependent_list_box').append($(
                '<div id="relationshipstatus_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="relationshipstatus" ' + 
                        'data-entity-id="' + rowId + '" class="badge relationshipstatus_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#relationshipstatus_dependent_list_box').on('click', '.relationshipstatus_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="relationshipstatus_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#relationshipstatus_dependent_list_box').find('#relationshipstatus_list_item_' + entityId).remove();

      
   });
   var sb_partyrel_rows_selected = [];
   var sb_partyrelTable =  $('#sb_partyrel-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partyrel-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "rel_type_txt" },

            { data: "from_role_txt" },

            { data: "to_role_txt" },

            { data: "status_txt" },
            { data: "name" },

            { data: "from_date" },

            { data: "to_date" },

            { data: "description" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partyrelationship_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyrelationship_parent_params').length) {
                        parent_params = parent_params + $('#partyrelationship_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partyrelationship&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyrelationship" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partyrel_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partyrel-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partyrelTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partyrel_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partyrel_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partyrel_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyrelTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partyrel-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partyrelTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partyrel-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partyrel-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partyrelTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyrelTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partyrelationship-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partyrel_rows_selected, function(index, rowId){

        $.each($('input[name="partyrelationship_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partyrelationship_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partyrelationship_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partyrelationship_dependent_list_box').append($(
                '<div id="partyrelationship_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partyrelationship" ' + 
                        'data-entity-id="' + rowId + '" class="badge partyrelationship_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partyrelationship_dependent_list_box').on('click', '.partyrelationship_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partyrelationship_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partyrelationship_dependent_list_box').find('#partyrelationship_list_item_' + entityId).remove();

      
   });
   var sb_partygroup_rows_selected = [];
   var sb_partygroupTable =  $('#sb_partygroup-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partygroup-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "business_unit_txt" },
            { data: "name" },

            { data: "pin" },

            { data: "description" },

            { data: "date_created" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partygroup_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partygroup_parent_params').length) {
                        parent_params = parent_params + $('#partygroup_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partygroup&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partygroup" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partygroup_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partygroup-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partygroupTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partygroup_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partygroup_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partygroup_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partygroupTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partygroup-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partygroupTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partygroup-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partygroup-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partygroupTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partygroupTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partygroup-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partygroup_rows_selected, function(index, rowId){

        $.each($('input[name="partygroup_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partygroup_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partygroup_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partygroup_dependent_list_box').append($(
                '<div id="partygroup_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partygroup" ' + 
                        'data-entity-id="' + rowId + '" class="badge partygroup_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partygroup_dependent_list_box').on('click', '.partygroup_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partygroup_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partygroup_dependent_list_box').find('#partygroup_list_item_' + entityId).remove();

      
   });
   var sb_person_rows_selected = [];
   var sb_personTable =  $('#sb_person-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_person-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "business_unit_txt" },
            { data: "first_name" },

            { data: "last_name" },

            { data: "gender" },

            { data: "date_of_birth" },

            { data: "id_number" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="person_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#person_parent_params').length) {
                        parent_params = parent_params + $('#person_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=person&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="person" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_person_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_person-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_personTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_person_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_person_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_person_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_personTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_person-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_personTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_person-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_person-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_personTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_personTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-person-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_person_rows_selected, function(index, rowId){

        $.each($('input[name="person_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'person_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#person_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#person_dependent_list_box').append($(
                '<div id="person_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="person" ' + 
                        'data-entity-id="' + rowId + '" class="badge person_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#person_dependent_list_box').on('click', '.person_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="person_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#person_dependent_list_box').find('#person_list_item_' + entityId).remove();

      
   });
   var sb_partyprofile_rows_selected = [];
   var sb_partyprofileTable =  $('#sb_partyprofile-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_partyprofile-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "party_txt" },

            { data: "default_unit_txt" },
            { data: "name" },

            { data: "display_name" },

            { data: "description" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="partyprofile_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyprofile_parent_params').length) {
                        parent_params = parent_params + $('#partyprofile_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=partyprofile&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyprofile" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_partyprofile_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_partyprofile-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_partyprofileTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_partyprofile_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_partyprofile_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_partyprofile_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyprofileTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_partyprofile-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_partyprofileTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_partyprofile-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_partyprofile-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_partyprofileTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_partyprofileTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-partyprofile-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_partyprofile_rows_selected, function(index, rowId){

        $.each($('input[name="partyprofile_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'partyprofile_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#partyprofile_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#partyprofile_dependent_list_box').append($(
                '<div id="partyprofile_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="partyprofile" ' + 
                        'data-entity-id="' + rowId + '" class="badge partyprofile_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#partyprofile_dependent_list_box').on('click', '.partyprofile_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="partyprofile_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#partyprofile_dependent_list_box').find('#partyprofile_list_item_' + entityId).remove();

      
   });
   var sb_billaccount_rows_selected = [];
   var sb_billaccountTable =  $('#sb_billaccount-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_billaccount-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "balance" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="billingaccount_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#billingaccount_parent_params').length) {
                        parent_params = parent_params + $('#billingaccount_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=billingaccount&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="billingaccount" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_billaccount_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_billaccount-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_billaccountTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_billaccount_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_billaccount_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_billaccount_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_billaccountTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_billaccount-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_billaccountTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_billaccount-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_billaccount-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_billaccountTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_billaccountTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-billingaccount-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_billaccount_rows_selected, function(index, rowId){

        $.each($('input[name="billingaccount_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'billingaccount_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#billingaccount_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#billingaccount_dependent_list_box').append($(
                '<div id="billingaccount_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="billingaccount" ' + 
                        'data-entity-id="' + rowId + '" class="badge billingaccount_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#billingaccount_dependent_list_box').on('click', '.billingaccount_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="billingaccount_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#billingaccount_dependent_list_box').find('#billingaccount_list_item_' + entityId).remove();

      
   });
   var sb_conversation_rows_selected = [];
   var sb_conversationTable =  $('#sb_conversation-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_conversation-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="conversation_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#conversation_parent_params').length) {
                        parent_params = parent_params + $('#conversation_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=conversation&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="conversation" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_conversation_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_conversation-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_conversationTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_conversation_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_conversation_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_conversation_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_conversationTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_conversation-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_conversationTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_conversation-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_conversation-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_conversationTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_conversationTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-conversation-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_conversation_rows_selected, function(index, rowId){

        $.each($('input[name="conversation_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'conversation_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#conversation_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#conversation_dependent_list_box').append($(
                '<div id="conversation_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="conversation" ' + 
                        'data-entity-id="' + rowId + '" class="badge conversation_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#conversation_dependent_list_box').on('click', '.conversation_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="conversation_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#conversation_dependent_list_box').find('#conversation_list_item_' + entityId).remove();

      
   });
   var sb_conuser_rows_selected = [];
   var sb_conuserTable =  $('#sb_conuser-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_conuser-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "conversation_txt" },

            { data: "con_user_txt" },
            { data: "name" },

            { data: "create_date" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="conversationuser_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#conversationuser_parent_params').length) {
                        parent_params = parent_params + $('#conversationuser_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=conversationuser&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="conversationuser" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_conuser_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_conuser-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_conuserTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_conuser_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_conuser_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_conuser_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_conuserTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_conuser-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_conuserTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_conuser-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_conuser-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_conuserTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_conuserTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-conversationuser-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_conuser_rows_selected, function(index, rowId){

        $.each($('input[name="conversationuser_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'conversationuser_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#conversationuser_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#conversationuser_dependent_list_box').append($(
                '<div id="conversationuser_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="conversationuser" ' + 
                        'data-entity-id="' + rowId + '" class="badge conversationuser_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#conversationuser_dependent_list_box').on('click', '.conversationuser_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="conversationuser_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#conversationuser_dependent_list_box').find('#conversationuser_list_item_' + entityId).remove();

      
   });
   var sb_message_rows_selected = [];
   var sb_messageTable =  $('#sb_message-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_message-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "conversation_txt" },

            { data: "owner_txt" },

            { data: "counter_party_txt" },
            { data: "name" },

            { data: "message" },

            { data: "message_date" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="message_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#message_parent_params').length) {
                        parent_params = parent_params + $('#message_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=message&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="message" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_message_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_message-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_messageTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_message_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_message_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_message_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_messageTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_message-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_messageTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_message-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_message-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_messageTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_messageTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-message-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_message_rows_selected, function(index, rowId){

        $.each($('input[name="message_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'message_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#message_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#message_dependent_list_box').append($(
                '<div id="message_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="message" ' + 
                        'data-entity-id="' + rowId + '" class="badge message_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#message_dependent_list_box').on('click', '.message_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="message_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#message_dependent_list_box').find('#message_list_item_' + entityId).remove();

      
   });
   var sb_messagesfiles_rows_selected = [];
   var sb_messagesfilesTable =  $('#sb_messagesfiles-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_messagesfiles-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "message_txt" },
            { data: "name" },

            { data: "description" },

            { data: "file_url" },

            { data: "file_size" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="messagefiles_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#messagefiles_parent_params').length) {
                        parent_params = parent_params + $('#messagefiles_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=messagefiles&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="messagefiles" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_messagesfiles_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_messagesfiles-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_messagesfilesTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_messagesfiles_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_messagesfiles_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_messagesfiles_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_messagesfilesTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_messagesfiles-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_messagesfilesTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_messagesfiles-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_messagesfiles-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_messagesfilesTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_messagesfilesTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-messagefiles-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_messagesfiles_rows_selected, function(index, rowId){

        $.each($('input[name="messagefiles_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'messagefiles_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#messagefiles_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#messagefiles_dependent_list_box').append($(
                '<div id="messagefiles_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="messagefiles" ' + 
                        'data-entity-id="' + rowId + '" class="badge messagefiles_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#messagefiles_dependent_list_box').on('click', '.messagefiles_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="messagefiles_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#messagefiles_dependent_list_box').find('#messagefiles_list_item_' + entityId).remove();

      
   });
   var sb_notifytype_rows_selected = [];
   var sb_notifytypeTable =  $('#sb_notifytype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_notifytype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

            { data: "title_template" },

            { data: "message_template" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="notificationtype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationtype_parent_params').length) {
                        parent_params = parent_params + $('#notificationtype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=notificationtype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationtype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_notifytype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_notifytype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_notifytypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_notifytype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_notifytype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_notifytype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifytypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_notifytype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_notifytypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_notifytype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_notifytype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_notifytypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifytypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-notificationtype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_notifytype_rows_selected, function(index, rowId){

        $.each($('input[name="notificationtype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'notificationtype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#notificationtype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#notificationtype_dependent_list_box').append($(
                '<div id="notificationtype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="notificationtype" ' + 
                        'data-entity-id="' + rowId + '" class="badge notificationtype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#notificationtype_dependent_list_box').on('click', '.notificationtype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="notificationtype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#notificationtype_dependent_list_box').find('#notificationtype_list_item_' + entityId).remove();

      
   });
   var sb_notifystatus_rows_selected = [];
   var sb_notifystatusTable =  $('#sb_notifystatus-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_notifystatus-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="notificationstatus_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationstatus_parent_params').length) {
                        parent_params = parent_params + $('#notificationstatus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=notificationstatus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationstatus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_notifystatus_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_notifystatus-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_notifystatusTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_notifystatus_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_notifystatus_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_notifystatus_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifystatusTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_notifystatus-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_notifystatusTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_notifystatus-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_notifystatus-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_notifystatusTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifystatusTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-notificationstatus-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_notifystatus_rows_selected, function(index, rowId){

        $.each($('input[name="notificationstatus_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'notificationstatus_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#notificationstatus_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#notificationstatus_dependent_list_box').append($(
                '<div id="notificationstatus_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="notificationstatus" ' + 
                        'data-entity-id="' + rowId + '" class="badge notificationstatus_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#notificationstatus_dependent_list_box').on('click', '.notificationstatus_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="notificationstatus_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#notificationstatus_dependent_list_box').find('#notificationstatus_list_item_' + entityId).remove();

      
   });
   var sb_notifylevel_rows_selected = [];
   var sb_notifylevelTable =  $('#sb_notifylevel-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_notifylevel-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="notificationlevel_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationlevel_parent_params').length) {
                        parent_params = parent_params + $('#notificationlevel_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=notificationlevel&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationlevel" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_notifylevel_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_notifylevel-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_notifylevelTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_notifylevel_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_notifylevel_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_notifylevel_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifylevelTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_notifylevel-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_notifylevelTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_notifylevel-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_notifylevel-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_notifylevelTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notifylevelTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-notificationlevel-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_notifylevel_rows_selected, function(index, rowId){

        $.each($('input[name="notificationlevel_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'notificationlevel_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#notificationlevel_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#notificationlevel_dependent_list_box').append($(
                '<div id="notificationlevel_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="notificationlevel" ' + 
                        'data-entity-id="' + rowId + '" class="badge notificationlevel_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#notificationlevel_dependent_list_box').on('click', '.notificationlevel_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="notificationlevel_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#notificationlevel_dependent_list_box').find('#notificationlevel_list_item_' + entityId).remove();

      
   });
   var sb_notification_rows_selected = [];
   var sb_notificationTable =  $('#sb_notification-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_notification-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

            { data: "n_owner_txt" },

            { data: "n_type_txt" },

            { data: "status_txt" },

            { data: "log_level_txt" },
            { data: "name" },

            { data: "description" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="notification_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notification_parent_params').length) {
                        parent_params = parent_params + $('#notification_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=notification&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notification" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_notification_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_notification-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_notificationTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_notification_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_notification_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_notification_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notificationTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_notification-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_notificationTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_notification-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_notification-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_notificationTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_notificationTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-notification-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_notification_rows_selected, function(index, rowId){

        $.each($('input[name="notification_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'notification_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#notification_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#notification_dependent_list_box').append($(
                '<div id="notification_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="notification" ' + 
                        'data-entity-id="' + rowId + '" class="badge notification_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#notification_dependent_list_box').on('click', '.notification_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="notification_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#notification_dependent_list_box').find('#notification_list_item_' + entityId).remove();

      
   });
   var sb_contactus_rows_selected = [];
   var sb_contactusTable =  $('#sb_contactus-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_contactus-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "subject" },

            { data: "name" },

            { data: "email" },

            { data: "b_name" },

            { data: "message" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="contactus_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#contactus_parent_params').length) {
                        parent_params = parent_params + $('#contactus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=contactus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="contactus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_contactus_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_contactus-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_contactusTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_contactus_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_contactus_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_contactus_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_contactusTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_contactus-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_contactusTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_contactus-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_contactus-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_contactusTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_contactusTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-contactus-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_contactus_rows_selected, function(index, rowId){

        $.each($('input[name="contactus_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'contactus_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#contactus_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#contactus_dependent_list_box').append($(
                '<div id="contactus_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="contactus" ' + 
                        'data-entity-id="' + rowId + '" class="badge contactus_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#contactus_dependent_list_box').on('click', '.contactus_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="contactus_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#contactus_dependent_list_box').find('#contactus_list_item_' + entityId).remove();

      
   });
   var sb_doctype_rows_selected = [];
   var sb_doctypeTable =  $('#sb_doctype-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_doctype-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="documenttype_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#documenttype_parent_params').length) {
                        parent_params = parent_params + $('#documenttype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=documenttype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="documenttype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_doctype_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_doctype-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_doctypeTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_doctype_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_doctype_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_doctype_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_doctypeTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_doctype-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_doctypeTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_doctype-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_doctype-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_doctypeTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_doctypeTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-documenttype-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_doctype_rows_selected, function(index, rowId){

        $.each($('input[name="documenttype_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'documenttype_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#documenttype_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#documenttype_dependent_list_box').append($(
                '<div id="documenttype_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="documenttype" ' + 
                        'data-entity-id="' + rowId + '" class="badge documenttype_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#documenttype_dependent_list_box').on('click', '.documenttype_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="documenttype_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#documenttype_dependent_list_box').find('#documenttype_list_item_' + entityId).remove();

      
   });
   var sb_docurgency_rows_selected = [];
   var sb_docurgencyTable =  $('#sb_docurgency-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_docurgency-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "date_value" },

            { data: "date_unit" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="urgency_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#urgency_parent_params').length) {
                        parent_params = parent_params + $('#urgency_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=urgency&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="urgency" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_docurgency_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_docurgency-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_docurgencyTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_docurgency_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_docurgency_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_docurgency_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_docurgencyTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_docurgency-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_docurgencyTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_docurgency-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_docurgency-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_docurgencyTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_docurgencyTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-urgency-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_docurgency_rows_selected, function(index, rowId){

        $.each($('input[name="urgency_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'urgency_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#urgency_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#urgency_dependent_list_box').append($(
                '<div id="urgency_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="urgency" ' + 
                        'data-entity-id="' + rowId + '" class="badge urgency_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#urgency_dependent_list_box').on('click', '.urgency_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="urgency_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#urgency_dependent_list_box').find('#urgency_list_item_' + entityId).remove();

      
   });
   var sb_noofpages_rows_selected = [];
   var sb_noofpagesTable =  $('#sb_noofpages-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_noofpages-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="noofpages_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#noofpages_parent_params').length) {
                        parent_params = parent_params + $('#noofpages_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=noofpages&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="noofpages" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_noofpages_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_noofpages-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_noofpagesTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_noofpages_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_noofpages_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_noofpages_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_noofpagesTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_noofpages-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_noofpagesTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_noofpages-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_noofpages-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_noofpagesTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_noofpagesTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-noofpages-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_noofpages_rows_selected, function(index, rowId){

        $.each($('input[name="noofpages_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'noofpages_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#noofpages_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#noofpages_dependent_list_box').append($(
                '<div id="noofpages_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="noofpages" ' + 
                        'data-entity-id="' + rowId + '" class="badge noofpages_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#noofpages_dependent_list_box').on('click', '.noofpages_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="noofpages_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#noofpages_dependent_list_box').find('#noofpages_list_item_' + entityId).remove();

      
   });
   var sb_subarea_rows_selected = [];
   var sb_subareaTable =  $('#sb_subarea-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_subarea-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="subjectarea_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#subjectarea_parent_params').length) {
                        parent_params = parent_params + $('#subjectarea_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=subjectarea&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="subjectarea" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_subarea_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_subarea-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_subareaTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_subarea_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_subarea_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_subarea_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_subareaTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_subarea-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_subareaTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_subarea-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_subarea-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_subareaTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_subareaTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-subjectarea-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_subarea_rows_selected, function(index, rowId){

        $.each($('input[name="subjectarea_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'subjectarea_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#subjectarea_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#subjectarea_dependent_list_box').append($(
                '<div id="subjectarea_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="subjectarea" ' + 
                        'data-entity-id="' + rowId + '" class="badge subjectarea_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#subjectarea_dependent_list_box').on('click', '.subjectarea_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="subjectarea_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#subjectarea_dependent_list_box').find('#subjectarea_list_item_' + entityId).remove();

      
   });
   var sb_alevel_rows_selected = [];
   var sb_alevelTable =  $('#sb_alevel-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_alevel-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="academiclevel_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#academiclevel_parent_params').length) {
                        parent_params = parent_params + $('#academiclevel_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=academiclevel&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="academiclevel" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_alevel_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_alevel-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_alevelTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_alevel_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_alevel_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_alevel_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_alevelTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_alevel-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_alevelTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_alevel-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_alevel-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_alevelTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_alevelTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-academiclevel-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_alevel_rows_selected, function(index, rowId){

        $.each($('input[name="academiclevel_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'academiclevel_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#academiclevel_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#academiclevel_dependent_list_box').append($(
                '<div id="academiclevel_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="academiclevel" ' + 
                        'data-entity-id="' + rowId + '" class="badge academiclevel_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#academiclevel_dependent_list_box').on('click', '.academiclevel_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="academiclevel_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#academiclevel_dependent_list_box').find('#academiclevel_list_item_' + entityId).remove();

      
   });
   var sb_wstyle_rows_selected = [];
   var sb_wstyleTable =  $('#sb_wstyle-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_wstyle-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "description" },

        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="writingstyle_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#writingstyle_parent_params').length) {
                        parent_params = parent_params + $('#writingstyle_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=writingstyle&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="writingstyle" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_wstyle_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_wstyle-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_wstyleTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_wstyle_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_wstyle_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_wstyle_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_wstyleTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_wstyle-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_wstyleTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_wstyle-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_wstyle-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_wstyleTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_wstyleTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-writingstyle-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_wstyle_rows_selected, function(index, rowId){

        $.each($('input[name="writingstyle_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'writingstyle_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#writingstyle_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#writingstyle_dependent_list_box').append($(
                '<div id="writingstyle_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="writingstyle" ' + 
                        'data-entity-id="' + rowId + '" class="badge writingstyle_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#writingstyle_dependent_list_box').on('click', '.writingstyle_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="writingstyle_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#writingstyle_dependent_list_box').find('#writingstyle_list_item_' + entityId).remove();

      
   });
   var sb_corder_rows_selected = [];
   var sb_corderTable =  $('#sb_corder-multi-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.form = $("#sb_corder-multi-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
            { data: "entity_code" },

            { data: "name" },

            { data: "email" },


            { data: "document_type_txt" },

            { data: "urgency_txt" },

            { data: "numpages_txt" },

            { data: "subject_area_txt" },

            { data: "academic_level_txt" },

            { data: "writing_style_txt" },
            { data: "description" },

            { data: "total" },


            { data: "business_unit_txt" },
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="contentorder_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#contentorder_parent_params').length) {
                        parent_params = parent_params + $('#contentorder_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + shadowcore_base_url.baseUrl + 'artifact=contentorder&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="contentorder" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, sb_corder_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#sb_corder-multi-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = sb_corderTable.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, sb_corder_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         sb_corder_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         sb_corder_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_corderTable);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#sb_corder-multi-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', sb_corderTable.table().container()).on('click', function(e){
      if(this.checked){
         $('#sb_corder-multi-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#sb_corder-multi-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   sb_corderTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(sb_corderTable);
   });

   /* Add all check rows in the data table */
   $('body').on('click', '#add-selected-contentorder-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#main-entity-post-name').val();
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(sb_corder_rows_selected, function(index, rowId){

        $.each($('input[name="contentorder_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Add the id of the selected row as a hidden input in the 
          // main form 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', 'contentorder_id[]')
                  .val(rowId)
           );
           // Get the value of the name column. Every entity data table has name and description columns
           var dependentInstanceName = $('#contentorder_' + rowId).data('dependent-instance-name');
           // Add an entry into the visual list of select instances
           $('#contentorder_dependent_list_box').append($(
                '<div id="contentorder_list_item_' + rowId + '"> ' + 
                    '<span data-entity-name="contentorder" ' + 
                        'data-entity-id="' + rowId + '" class="badge contentorder_dependent_list_item" ' + 
                        'style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + 
                '</div>').attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#contentorder_dependent_list_box').on('click', '.contentorder_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#main-entity-post-name').val();
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="contentorder_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        if($(rowId).val() == entityId) {
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#contentorder_dependent_list_box').find('#contentorder_list_item_' + entityId).remove();

      
   });

});