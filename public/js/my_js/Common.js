let qrcode = "";
let imgResultQrCode = '';
let qrCodeTitle = '';
let genQRCodeName = '';

$(document).ready(function(){	
	$(document).on('click', '.aGenerateBarcode', function(){
		let barcode = $(this).attr('barcode');
		qrCodeTitle = $(this).attr('title');
		genQRCodeName = $(this).attr('name');
		qrcode = barcode;
	    $.ajax({
	        url: "generate_qrcode",
	        method: "get",
	        data: {
	        	qrcode: barcode
	        },
	        // dataType: "json",
	        beforeSend: function(){
	            
	        },
	        success: function(JsonObject){
				if(JsonObject['result'] == 1){
					$("#imgGenBarcode").attr("src", JsonObject['qrcode']);
					imgResultQrCode = JsonObject['qrcode'];
				}
				$("#lblGenBarcodeVal").text(barcode);
	        },
	        error: function(data, xhr, status){
	            alert('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
	            
	        }
	    });
	});

	$("#btnPrintBarcode").click(function(){

		popup = window.open();
        // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
        let content = '';
        content += '<html>';
        content += '<head>';
          content += '<title></title>';
          content += '<style type="text/css">';
            content += '.rotated {';
              // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
              content += 'border: 2px solid black;';
              content += 'width: 150px;';
              content += 'position: absolute;';
              content += 'left: 15px;';
              content += 'top: 15px;';
            content += '}';
          content += '</style>';
        content += '</head>';
        content += '<body>';
          //content += '<br><br><br>';
          content += '<center>';
          content += '<div class="rotated">';
          content += '<table>';
          content += '<tr>';
          content += '<td>';
          content += '<center>';
          content += '<img src="' + imgResultQrCode + '" style="max-width: 70px;">';
          // content += '<br>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial;">' + genUserqrcode + '</label>';
          content += '</center>';
          content += '</td>';
          content += '<td>';
          content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + qrCodeTitle + '</label>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">PO NO.:</label>';
          content += '<br>';
          content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 10px;">' + genQRCodeName + '</label>';
          // content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 10px;">450198990900010</label>';
          content += '</td>';
          content += '</tr>';
          content += '</table>';
          content += '</div>';
          content += '</center>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
	});
});