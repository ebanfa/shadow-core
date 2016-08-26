
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
   // Array holding selected row IDs
<#list module.entities as modEntity>
<#if modEntity.name == "Unit" || modEntity.name == "Charge" || modEntity.name == "Term">

   var ${modEntity.postName}_rows_selected = [];
   var ${modEntity.postName}Table =  $('#${modEntity.postName}-list-table').DataTable({
        "ajax": {
            'type': 'POST',
            'url': ${application.name?lower_case}_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_${modEntity.postName}_ajax';
               d.form = $("#${modEntity.postName}-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
<#list modEntity.fields as field>
    <#if field.listField == "Y">
        <#if field.relationshipField == "N">
            { data: "${field.name}" },
        </#if>

        <#if field.relationshipField == "Y">
            { data: "${field.name}_txt" },
        </#if>
    </#if>
</#list>
        ],
        columnDefs: [
            {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, row){
                    return '<input id="${modEntity.name?lower_case}_' + row.id + '" type="checkbox" value="' + row.id + '" data-dependent-instance-name="' + row.name + '">';
                },
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#${modEntity.name?lower_case}_parent_params').length) {
                        parent_params = parent_params + $('#${modEntity.name?lower_case}_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="../page/?type=entity&artifact=${modEntity.name?lower_case}&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="${modEntity.name?lower_case}" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ],
        'order': [[1, 'asc']],
        'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, ${modEntity.postName}_rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
        }
    });

   // Handle click on checkbox
   $('#${modEntity.postName}-list-table tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = ${modEntity.postName}Table.row($row).data();
      // Get row ID
      var rowId = $(this).val();
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, ${modEntity.postName}_rows_selected);
      console.log('This is index:' + index);
      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         console.log('This is checked and index:' + index);
         ${modEntity.postName}_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         ${modEntity.postName}_rows_selected.splice(index, 1);
         console.log('This is not checked and index:' + index);
      }
      if(this.checked){
         $row.css('background-color', 'rgba(255, 152, 0, 0.5)');
      } else {
         $row.css('background-color', 'rgba(255, 152, 0, 0)');
      }
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(${modEntity.postName}Table);
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#${modEntity.postName}-list-table').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', ${modEntity.postName}Table.table().container()).on('click', function(e){
      if(this.checked){
         $('#${modEntity.postName}-list-table tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#${modEntity.postName}-list-table tbody input[type="checkbox"]:checked').trigger('click');
      }
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   ${modEntity.postName}Table.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(${modEntity.postName}Table);
   });

   $('body').on('click', '#add-selected-${modEntity.name?lower_case}-list-btn', function(e){
      e.preventDefault();
      var page_artifact_form = $('#page-artifact-name').val() + '_form';
      // Iterate over all selected checkboxes
      var idExists = false;
      $.each(${modEntity.postName}_rows_selected, function(index, rowId){

        $.each($('input[name="${modEntity.name?lower_case}_id[]"]'), function(indexx){ 
            var valueToAdd = $(this).val();
            if(valueToAdd === rowId){
              idExists = true;
            }

        });
        if(!idExists){
          // Create a hidden element 
           $('#' + page_artifact_form).append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', '${modEntity.name?lower_case}_id[]')
                  .val(rowId)
           );
           var dependentInstanceName = $('#${modEntity.name?lower_case}_' + rowId).data('dependent-instance-name');
           // Add the item to the list 
           $('#${modEntity.name?lower_case}_dependent_list_box').append($('<div id="${modEntity.name?lower_case}_list_item_' + rowId + '"><span data-entity-name="${modEntity.name?lower_case}" data-entity-id="' + rowId + '" class="badge ${modEntity.name?lower_case}_dependent_list_item" style="cursor: pointer; cursor: hand; background-color: red">X</span>' + dependentInstanceName + '</div>')
                  .attr('class', 'list-group-item'));
        }
      });
   });
    // Handle click on table cells with checkboxes
   $('#${modEntity.name?lower_case}_dependent_list_box').on('click', '.${modEntity.name?lower_case}_dependent_list_item', function(e){
      var entityId = $(this).data('entity-id');
      var entityName = $(this).data('entity-name');
      var page_artifact_form = $('#page-artifact-name').val() + '_form';
      // first remove the hidden form field and then the list box item
      var existingIds = $('#' + page_artifact_form).find('input[name="${modEntity.name?lower_case}_id[]"]');
      $.each(existingIds, function(index, rowId){ 
        console.log('hecking entityId ' + entityId +' against row value' + $(rowId).val());
        if($(rowId).val() == entityId) {
          console.log('Calling>>>>>>>>>>>>>>>>>>Removing id :' + $(rowId).val());
          $(rowId).remove();
        }

      });
      // then remove the list box item
      $('#${modEntity.name?lower_case}_dependent_list_box').find('#${modEntity.name?lower_case}_list_item_' + rowId).remove();

      
   });

</#if>
</#list>

});