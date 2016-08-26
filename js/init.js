/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function (BlitzDocument, $, undefined) {
    //Public Property
    BlitzDocument.name = "Blitz Document App";
    BlitzDocument.version = "0.5";
    BlitzDocument.pageContentContainer = ".page-panel";
    BlitzDocument.questionDetailsDialog = "#question-details-dialog";
    BlitzDocument.orderProcessingErrorDialog = "#order-error-processing-dialog";
    BlitzDocument.signinContentContainer = "#wrapper";
    BlitzDocument.signinSuccessContainer = "#signin-success-container";
    BlitzDocument.signinErrorContainer = "#signin-success-container";

    /* 
     * Called when the order form is submitted.
     */
    BlitzDocument.onOrderFormProcessingStarted = function () {
        $(BlitzDocument.pageContentContainer).showPageLoader();
        
    };
    
    /* 
     * Called when order form processing returns success from the server.
     */
    BlitzDocument.onOrderFormProcessingSuccess = function (responseData) {
        $("#success").html(responseData.data.message);
    };

    /* 
     * Called when order form processing returns an error from the server.
     */
    BlitzDocument.onOrderFormProcessingError = function (responseData) {
        $.fn.overlayout();
        $(BlitzDocument.orderProcessingErrorDialog).modal('show');
    };

    /* 
     * Called when the order form is submitted.
     */
    BlitzDocument.onSigninFormProcessingStarted = function () {
       $(BlitzDocument.signinContentContainer).showPageLoader();
    };

    /* 
     * Called when signin form processing returns success from the server.
     */
    BlitzDocument.onSigninFormProcessingSuccess = function (responseData) {
        $(BlitzDocument.signinSuccessContainer).empty();
        $(BlitzDocument.signinSuccessContainer).html(responseData.data.message);
    };

    /* 
     * Called when signin form processing returns an error from the server
     */
    BlitzDocument.onSigninFormProcessingError = function (responseData) {
        $.fn.overlayout();
        $(BlitzDocument.signinErrorContainer).empty();
        $(BlitzDocument.signinErrorContainer).html(responseData.data.message);
    };

    /* 
     * Called when the signup form is submitted.
     */
    BlitzDocument.onSignupFormProcessingStarted = function () {
        $(BlitzDocument.signinContentContainer).overlay();
    };

    /* 
     * Called when signup form processing returns success from the server.
     */
    BlitzDocument.onSignupFormProcessingSuccess = function (responseData) {
        $(BlitzDocument.signinSuccessContainer).html(responseData.data.message);
    };

    /* 
     * Called when signup form procession returns an error from server
     */
    BlitzDocument.onSignupFormProcessingError = function (responseData) {
        $.fn.overlayout();
        $(BlitzDocument.signinProcessingErrorDialog).modal('show');
    };
    
    BlitzDocument.doOrderFormFileSelection = function (inputField, filesArray) {
        // Loop through all files and add to form data
        $.each(inputField.files, function (i, file) {
            // Flag to check for duplicates
            var isDuplicate = false;
            // Loop through the files previously selected by the user...
            $.each(filesArray, function (j, selectedFile) 
            {
                // Check is there is file with same name
                if(file.name === selectedFile.name) {
                    isDuplicate = true;
                }
            });
            // If the file is not a duplicate then add to ui and to file list
            if(!isDuplicate) {
                BlitzDocument.addSelectedFileToUI(file);
                filesArray.push(file);
            }
        });
    };
    
    BlitzDocument.doRemoveOrderFormFile = function($fileEntryLink, filesArray){
        var fileName = $fileEntryLink.data('file-name');
        $fileEntryLink.parent('.file-entry-div').remove();
        // Loop through selected files and remove the referenced file
        console.log('doRemoveOrderFormFile loop starting');
        $.each(filesArray, function(i, file) {
            // Check is there is file with same name
            console.log('Looping through files for removal' + i);
            if(file.name === fileName) {
                console.log('Splicing' + fileName);
                filesArray.splice(i,1);
            }
            
        });
    };
    
    BlitzDocument.addSelectedFileToUI = function (file) {
        var fileEntryDiv = $('<div class="file-entry-div" style="padding: 5px; margin: 5px; background-color: #ddd"></div>');
        fileEntryDiv.html('<span>' + file.name + '</span>');
        fileEntryDiv.append('<a href="#" class="selected-file-entry pull-right" data-file-name="' + file.name + '" style="font-weight: bold">X</a>')
        $('#selected_files_list').append(fileEntryDiv);
    };
    
    BlitzDocument.doProcessOrderForm = function($form, files) {
        BlitzDocument.onOrderFormProcessingStarted();
        var formData = new FormData(), params = $form.serializeArray();
        // Copy the files selected by the user into the formData
        $.each(files, function(i, file) {
            formData.append('order_attachment[]', file);
        });
        // Copy other params from the form into the formData
        $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });
        // As required by wordpress
        formData.append('action', 'do_content_order_ajax');
        // Make the Ajax call
        $.ajax({
            url: blitzdocument_ajax_script.ajaxurl,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(responseData) {
                if (responseData.success) {
                    BlitzDocument.onOrderFormProcessingSuccess(responseData);
                }
                else {
                    BlitzDocument.onOrderFormProcessingError(responseData);
                }
            }
        });
    };
    
    /* 
     * Calculates the cost of an order.
     */
    BlitzDocument.doOrderFormCalculation = function () {
        var orderForm = document.getElementById('contactForm');
        var orderCostPerPage = 0;
        var orderTotalCost = 0;
        var single = orderForm.o_interval.checked;
        var number = orderForm.numpages;

        var discount = orderForm.discount_percent_h.value;
        var wthdy = '';
        var wthdyx = '';
        var oc = 8.00 * doTypeOfDocumentCost(orderForm.document_type) * doAcademicLevelCost(orderForm.academic_level) * doUrgencyCost(orderForm.urgency) * doSubjectAreaCost(orderForm.subject_area);
        //var oc = 9.25 * doTypeOfDocumentCost(orderForm.document_type) * doUrgencyCost(orderForm.urgency) * doSubjectAreaCost(orderForm.subject_area) ;
        orderCostPerPage = (oc - (oc) * discount / 100) + doVasPP(document.getElementsByName('vas_id[]'));
        if (single == true) {
            orderCostPerPage = orderCostPerPage * 2;
            oc = oc * 2;
            number.options[0].value = '1';
            number.options[0].text = '1 page/approx 550 words';
            document.getElementById("num_pg_ord").innerHTML = 'approx 550 words per page';
            for (i = 1; i < number.length; i++) {

                number.options[i].text = (i + 1) + ' pages/approx ' + (2 * (i + 1) * 275) + ' words';
            }
        } else {
            number.options[0].value = '1';
            number.options[0].text = '1 page/approx 275 words';
            document.getElementById("num_pg_ord").innerHTML = 'approx 275 words per page';
            for (i = 1; i < number.length; i++) {

                number.options[i].text = (i + 1) + ' pages/approx ' + ((i + 1) * 275) + ' words';
            }
        }
        number.options[number.selectedIndex].selected = true;
        wthdy = Math.round(orderCostPerPage * Math.pow(10, 2)) / Math.pow(10, 2);
        
        document.getElementById("cost_per_page").innerHTML = wthdy;	
        document.getElementById("costperpage").value = wthdy; 	
        
        orderForm.MTIuOTUYGREXGHNMKJGT23467GGFDSSSbbbbbIOK.value = encode64(wthdy);
        wthdyx = Math.round((orderCostPerPage * number.options[number.selectedIndex].value + doVasPO(document.getElementsByName('vas_id[]'))) * Math.pow(10, 2)) / Math.pow(10, 2);

        document.getElementById("total").value = wthdyx;
        document.getElementById("total_price").innerHTML = '$' + wthdyx;
        orderForm.MMNBGFREWQASCXZSOPJHGVNMTIuOTU.value = wthdyx;
    }

    function doTypeOfDocumentCost(tod) {
        document.getElementById("document_type_txt").value = tod.options[tod.selectedIndex].text;
        if (tod.options[tod.selectedIndex].value == 0) {
            return 1.00
        } else if (tod.options[tod.selectedIndex].value == 1) {
            return 1.00
        }
        else if (tod.options[tod.selectedIndex].value == 2) {
            return 1.20
        }
        else if (tod.options[tod.selectedIndex].value == 3) {
            return 1.12
        }
        else if (tod.options[tod.selectedIndex].value == 4) {
            return 1.12
        }
        else if (tod.options[tod.selectedIndex].value == 5) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 6) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 7) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 8) {
            return 1.40
        }
        else if (tod.options[tod.selectedIndex].value == 9) {
            return 1.40
        }
        else if (tod.options[tod.selectedIndex].value == 10) {
            return 1.40
        }
        else if (tod.options[tod.selectedIndex].value == 11) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 12) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 13) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 14) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 15) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 16) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 17) {
            return 1.30
        }
        else if (tod.options[tod.selectedIndex].value == 18) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 19) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 20) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 21) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 22) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 23) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 24) {
            return 0.50
        }
        else if (tod.options[tod.selectedIndex].value == 25) {
            return 0.40
        }
        else if (tod.options[tod.selectedIndex].value == 26) {
            return 0.40
        }
        else if (tod.options[tod.selectedIndex].value == 27) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 28) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 29) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 30) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 31) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 32) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 33) {
            return 1
        }
        else if (tod.options[tod.selectedIndex].value == 34) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 35) {
            return 1.10
        }
        else if (tod.options[tod.selectedIndex].value == 36) {
            return 1.27
        }
        else if (tod.options[tod.selectedIndex].value == 37) {
            return 0.25
        }
        else if (tod.options[tod.selectedIndex].value == 38) {
            return 1.50
        }
    }

    function doAcademicLevelCost(al) {
        document.getElementById("academic_level_txt").value = al.options[al.selectedIndex].text;
        if (al.options[al.selectedIndex].value == 1) {
            return 1.00
        } else if (al.options[al.selectedIndex].value == 2) {
            return 1.20
        } else if (al.options[al.selectedIndex].value == 3) {
            return 1.30
        } else if (al.options[al.selectedIndex].value == 4) {
            return 1.40
        }
    }

    function doUrgencyCost(urgency) {

        document.getElementById("urgency_txt").value = urgency.options[urgency.selectedIndex].text;
        if (urgency.options[urgency.selectedIndex].value == 2) {
            return 3.00
        } else if (urgency.options[urgency.selectedIndex].value == 3) {
            return 2.60
        } else if (urgency.options[urgency.selectedIndex].value == 4) {
            return 2.20
        } else if (urgency.options[urgency.selectedIndex].value == 5) {
            return 1.90
        } else if (urgency.options[urgency.selectedIndex].value == 6) {
            return 1.75
        } else if (urgency.options[urgency.selectedIndex].value == 7) {
            return 1.65
        } else if (urgency.options[urgency.selectedIndex].value == 8) {
            return 1.40
        } else if (urgency.options[urgency.selectedIndex].value == 9) {
            return 1.15
        } else if (urgency.options[urgency.selectedIndex].value == 10) {
            return 1.15
        } else if (urgency.options[urgency.selectedIndex].value == 11) {
            return 1.15
        } else if (urgency.options[urgency.selectedIndex].value == 1) {
            return 3.50
        }
    }

    function doSubjectAreaCost(subject) {

        document.getElementById("subject_area_txt").value = subject.options[subject.selectedIndex].text;
        if (subject.options[subject.selectedIndex].value == 18) {
            return 1.20
        }
        else if (subject.options[subject.selectedIndex].value == 10) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 12) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 15) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 17) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 13) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 16) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 18) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 11) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 14) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 112) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 52) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 111) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 102) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 105) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 107) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 103) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 104) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 115) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 53) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 60) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 61) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 58) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 62) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 59) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 57) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 63) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 64) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 87) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 93) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 89) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 88) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 90) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 67) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 9) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 24) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 36) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 38) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 37) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 42) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 41) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 44) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 45) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 40) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 39) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 43) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 47) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 49) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 48) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 7) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 2) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 4) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 5) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 6) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 3) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 116) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 54) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 56) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 51) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 94) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 99) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 97) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 101) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 95) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 100) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 96) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 78) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 85) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 113) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 86) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 83) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 79) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 80) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 28) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 110) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 29) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 21) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 108) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 22) {
            return 1.00
        }
        else if (subject.options[subject.selectedIndex].value == 65) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 71) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 70) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 72) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 73) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 75) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 77) {
            return 1.45
        }
        else if (subject.options[subject.selectedIndex].value == 114) {
            return 1.00
        }

    }

    function doCurrencyRate(curr) {
        document.getElementById("curr_txt").value = curr.options[curr.selectedIndex].text;
        if (curr.options[curr.selectedIndex].value == 1) {
            return 1.00
        } else if (curr.options[curr.selectedIndex].value == 2) {
            return 0.60
        } else if (curr.options[curr.selectedIndex].value == 3) {
            return 0.93
        } else if (curr.options[curr.selectedIndex].value == 4) {
            return 0.92
        } else if (curr.options[curr.selectedIndex].value == 5) {
            return 0.68
        }
    }

    var pp = [];
    var po = [];
    pp[3] = 2.95;
    po[6] = 9.95;

    function doVasPP(vas) {
        var return_sum = 0;
        for (var i = 0; i < vas.length; i++) {
            if ((vas[i].checked == true) && (vas[i].id.indexOf('page') != -1) && (!isNaN(pp[vas[i].value]))) {
                return_sum += pp[vas[i].value];
            }
        }
        return return_sum;
    }

    function doVasPO(vas) {
        var return_sum = 0;
        for (var i = 0; i < vas.length; i++) {
            if ((vas[i].checked == true) && (vas[i].id.indexOf('order') != -1) && (!isNaN(po[vas[i].value]))) {
                return_sum += po[vas[i].value];
            }
        }
        return return_sum;
    }

    var keyStr = "ABCDEFGHIJKLMNOP" +
            "QRSTUVWXYZabcdef" +
            "ghijklmnopqrstuv" +
            "wxyz0123456789+/" +
            "=";

    function encode64(input) {
        input = escape(input);
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        do {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
                    keyStr.charAt(enc1) +
                    keyStr.charAt(enc2) +
                    keyStr.charAt(enc3) +
                    keyStr.charAt(enc4);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);

        return output;
    }

    function decode64(input) {
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
        var base64test = /[^A-Za-z0-9\+\/\=]/g;
        if (base64test.exec(input)) {
            alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
        }
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        do {
            enc1 = keyStr.indexOf(input.charAt(i++));
            enc2 = keyStr.indexOf(input.charAt(i++));
            enc3 = keyStr.indexOf(input.charAt(i++));
            enc4 = keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";

        } while (i < input.length);

        return unescape(output);
    }

}(window.BlitzDocument = window.BlitzDocument || {}, jQuery));


$.fn.showPageLoader = function () 
{
    $('.loader-wrap').removeClass("hiding");
    $('.loader-wrap').removeClass("hide");
    $('.page-section').addClass("hiding");
};
$.fn.hidePageLoader = function () 
{
    $('.page-section').removeClass("hiding");
    $('.loader-wrap').addClass("hiding");
    $('.loader-wrap').addClass("hide");
};
