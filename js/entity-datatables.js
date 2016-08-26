
jQuery(document).ready(function($)
{

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_currency-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'currency';
               d.form = $("#sb_currency-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#currency_parent_params').length) {
                        parent_params = parent_params + $('#currency_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=currency&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="currency" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_loctype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'locationtype';
               d.form = $("#sb_loctype-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#locationtype_parent_params').length) {
                        parent_params = parent_params + $('#locationtype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=locationtype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="locationtype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_location-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'location';
               d.form = $("#sb_location-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#location_parent_params').length) {
                        parent_params = parent_params + $('#location_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=location&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="location" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_business-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'business';
               d.form = $("#sb_business-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#business_parent_params').length) {
                        parent_params = parent_params + $('#business_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=business&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="business" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_businessunit-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'businessunit';
               d.form = $("#sb_businessunit-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#businessunit_parent_params').length) {
                        parent_params = parent_params + $('#businessunit_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=businessunit&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="businessunit" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partycat-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partycategory';
               d.form = $("#sb_partycat-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partycategory_parent_params').length) {
                        parent_params = parent_params + $('#partycategory_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partycategory&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partycategory" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partytype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partytype';
               d.form = $("#sb_partytype-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 

                { data: "party_category_txt" },
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partytype_parent_params').length) {
                        parent_params = parent_params + $('#partytype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partytype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partytype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_roletype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'roletype';
               d.form = $("#sb_roletype-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#roletype_parent_params').length) {
                        parent_params = parent_params + $('#roletype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=roletype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="roletype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    $('#sb_party-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'party';
               d.form = $("#sb_party-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var role = '';
                    if($('#role').length) { role = '&role=' + $('#role').val(); }

                    var parent_params = '';
                    if($('#party_parent_params').length) {
                        parent_params = parent_params + $('#party_parent_params').val(); 
                    }

                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=party&id=' + row.id + role + '&page_action=view' + '" data-related-artifact-name="party" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });


    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partyrole-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partyrole';
               d.form = $("#sb_partyrole-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyrole_parent_params').length) {
                        parent_params = parent_params + $('#partyrole_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partyrole&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyrole" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_reltype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'relationshiptype';
               d.form = $("#sb_reltype-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#relationshiptype_parent_params').length) {
                        parent_params = parent_params + $('#relationshiptype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=relationshiptype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="relationshiptype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_relstatus-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'relationshipstatus';
               d.form = $("#sb_relstatus-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#relationshipstatus_parent_params').length) {
                        parent_params = parent_params + $('#relationshipstatus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=relationshipstatus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="relationshipstatus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partyrel-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partyrelationship';
               d.form = $("#sb_partyrel-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyrelationship_parent_params').length) {
                        parent_params = parent_params + $('#partyrelationship_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partyrelationship&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyrelationship" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partygroup-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partygroup';
               d.form = $("#sb_partygroup-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partygroup_parent_params').length) {
                        parent_params = parent_params + $('#partygroup_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partygroup&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partygroup" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_person-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'person';
               d.form = $("#sb_person-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#person_parent_params').length) {
                        parent_params = parent_params + $('#person_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=person&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="person" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_partyprofile-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'partyprofile';
               d.form = $("#sb_partyprofile-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#partyprofile_parent_params').length) {
                        parent_params = parent_params + $('#partyprofile_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=partyprofile&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="partyprofile" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_billaccount-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'billingaccount';
               d.form = $("#sb_billaccount-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "balance" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#billingaccount_parent_params').length) {
                        parent_params = parent_params + $('#billingaccount_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=billingaccount&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="billingaccount" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_conversation-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'conversation';
               d.form = $("#sb_conversation-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#conversation_parent_params').length) {
                        parent_params = parent_params + $('#conversation_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=conversation&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="conversation" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_conuser-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'conversationuser';
               d.form = $("#sb_conuser-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#conversationuser_parent_params').length) {
                        parent_params = parent_params + $('#conversationuser_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=conversationuser&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="conversationuser" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_message-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'message';
               d.form = $("#sb_message-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#message_parent_params').length) {
                        parent_params = parent_params + $('#message_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=message&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="message" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_messagesfiles-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'messagefiles';
               d.form = $("#sb_messagesfiles-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#messagefiles_parent_params').length) {
                        parent_params = parent_params + $('#messagefiles_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=messagefiles&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="messagefiles" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_notifytype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'notificationtype';
               d.form = $("#sb_notifytype-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationtype_parent_params').length) {
                        parent_params = parent_params + $('#notificationtype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=notificationtype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationtype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_notifystatus-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'notificationstatus';
               d.form = $("#sb_notifystatus-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationstatus_parent_params').length) {
                        parent_params = parent_params + $('#notificationstatus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=notificationstatus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationstatus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_notifylevel-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'notificationlevel';
               d.form = $("#sb_notifylevel-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notificationlevel_parent_params').length) {
                        parent_params = parent_params + $('#notificationlevel_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=notificationlevel&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notificationlevel" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_notification-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'notification';
               d.form = $("#sb_notification-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#notification_parent_params').length) {
                        parent_params = parent_params + $('#notification_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=notification&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="notification" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_contactus-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'contactus';
               d.form = $("#sb_contactus-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#contactus_parent_params').length) {
                        parent_params = parent_params + $('#contactus_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=contactus&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="contactus" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_doctype-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'documenttype';
               d.form = $("#sb_doctype-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#documenttype_parent_params').length) {
                        parent_params = parent_params + $('#documenttype_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=documenttype&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="documenttype" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_docurgency-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'urgency';
               d.form = $("#sb_docurgency-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#urgency_parent_params').length) {
                        parent_params = parent_params + $('#urgency_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=urgency&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="urgency" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_noofpages-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'noofpages';
               d.form = $("#sb_noofpages-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#noofpages_parent_params').length) {
                        parent_params = parent_params + $('#noofpages_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=noofpages&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="noofpages" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_subarea-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'subjectarea';
               d.form = $("#sb_subarea-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#subjectarea_parent_params').length) {
                        parent_params = parent_params + $('#subjectarea_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=subjectarea&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="subjectarea" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_alevel-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'academiclevel';
               d.form = $("#sb_alevel-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#academiclevel_parent_params').length) {
                        parent_params = parent_params + $('#academiclevel_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=academiclevel&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="academiclevel" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_wstyle-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'writingstyle';
               d.form = $("#sb_wstyle-list-form").serializeArray();
            },
        },
        columns: [
            { data: "id" }, 
                { data: "entity_code" },

                { data: "name" },

                { data: "description" },

        ],
        columnDefs: [
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#writingstyle_parent_params').length) {
                        parent_params = parent_params + $('#writingstyle_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=writingstyle&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="writingstyle" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });

    var baseUrl = shadowcore_base_url.baseUrl;

    
    $('#sb_corder-table').dataTable({
        "ajax": {
            'type': 'POST',
            'url': shadowcore_ajax_script.ajaxurl,
            'data': function(d){
               d.action = 'find_entity_ajax';
               d.artifact = 'contentorder';
               d.form = $("#sb_corder-list-form").serializeArray();
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
            { "visible": false,  "targets": 0 },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    var parent_params = '';
                    if($('#contentorder_parent_params').length) {
                        parent_params = parent_params + $('#contentorder_parent_params').val(); 
                    }
                    return '<a class="data-table-link" href="' + baseUrl + 'artifact=contentorder&id=' + row.id + '&page_action=view' + parent_params + '" data-related-artifact-name="contentorder" data-related-instance-name="' + row.name + '" data-related-instance-id="' + row.id + '">' + data +  '</a>';
                },
                "targets": 1
            }
        ]
    });



});
