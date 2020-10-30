<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	

	<?php
		$iid = $_GET['id'];  //location.href.replace(/^.*?\=/, '');
		//print_r($_REQUEST);

		include 'dbConfig.php';
		$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
		mysql_select_db($database) or die("Error connecting to db."); 

		$Q="SELECT firstName,lastName FROM patients WHERE id='$iid'";
		$R=mysql_query($Q);
		//print $Q.mysql_error();
		list($ffirstName,$llastName)=mysql_fetch_row($R);

	?>




 	<script type="text/javascript">

 		function weightRenderer(srcdata){
 			var data = [[]];
 			//alert(JSON.stringify(srcdata[0]));
 			for(var i in srcdata) {
 				data[0].push(srcdata[i].weight);
 			}
 			return data;
 		}

 		function waistMeasureRenderer(srcdata){
 			var data = [[]];
 			//alert(JSON.stringify(srcdata[0]));
 			for(var i in srcdata) {
 				data[0].push(srcdata[i].waistMeasure);
 			}
 			return data;
 		}

 		function hipMeasureRenderer(srcdata){
 			var data = [[]];
 			//alert(JSON.stringify(srcdata[0]));
 			for(var i in srcdata) {
 				data[0].push(srcdata[i].hipMeasure);
 			}
 			return data;
 		}

 		function armMeasureRenderer(srcdata){
 			var data = [[]];
 			//alert(JSON.stringify(srcdata[0]));
 			for(var i in srcdata) {
 				data[0].push(srcdata[i].armMeasure);
 			}
 			return data;
 		}

 		

		jQuery(document).ready(function(){ 

			var destId = location.href.replace(/^.*?\=/, '');
			$('#patientId').attr('value', destId);




			$(function() {
        		$( "#datepicker" ).datepicker({
            	changeMonth: true,
            	changeYear: true
       			});

        		$( "#format" ).change(function() {
            		$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );
       			});

   			 });

			var destId = location.href.replace(/^.*?\=/, '');


			$(function() {
		        $("#dialogSel").dialog({
		            modal: true,
		            autoOpen: false,
		            buttons: {
		                Ok: function() {
		                    $( this ).dialog( "close" );
		                }
		            }
		        });
		    });

		 
			var progressGrid = jQuery("#progress").jqGrid({
			    url:'progress.php?eid='+destId,
			    datatype: "json",
			    mtype: 'POST',
			    colNames:['Measurement Nr', 'Date', 'Weight, kg','Waist Measure, cm','Hip Measure, cm','Arm Measure, cm'],
			    //colNames:['Date', 'Weight','Waist Measure','Hip Measure','Arm Measure'],
			    colModel:[						
					{name:'Nr',index:'Nr',width:65,editable:false},
		            {name:'date',index:'date',sortable:'false', width:100, editable:true,editrules:{required: true},
		            	editoptions:{
		            		size:20,
		            		maxlength:60, 
	               			dataInit:function(el){ 
	               				$(el).datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,date:['1930','2050']}) 
	               			}
						}
					},
		            {name:'weight',index:'weight',sortable:'false', width:100, editable:true,editoptions:{maxlength:30},
		            	editrules:{required:true, custom:true, custom_func:weightValidation}
		        	},
		            {name:'waistMeasure',index:'waistMeasure',sortable:'false', width:100, editable:true,editoptions:{maxlength:30},
		            	editrules:{required:true, custom:true, custom_func:waistValidation}
		        	},
		            {name:'hipMeasure',index:'hipMeasure',sortable:'false', width:100, editable:true,editoptions:{maxlength:30},
		            	editrules:{required:true, custom:true, custom_func:hipsValidation}
		        	},
		            {name:'armMeasure',index:'armMeasure',sortable:'false', width:100, editable:true,editoptions:{maxlength:30},
		            	editrules:{required:true, custom:true, custom_func:armsValidation}
		        	},
			    ],
			    rowNum:20,
			    rowList:[10,20,30],
			    multiselect: true,
			    pager: jQuery('#pgProgress'),
			    viewrecords: true,
			    caption:"Progress",
			    rownumbers: true,
			    editurl:'progress.php?eid='+destId,

			    				
				
				gridComplete : function() {
					var a=jQuery("#progress").getGridParam('records');
					//alert(a);
					if(a>0)
					{
						var myData = jQuery("#progress").jqGrid('getRowData');
						//alert(myData);						
					
						var weightPlot = $.jqplot('weightDiv',myData,{
		     				//title: 'Weight Data',
		      				dataRenderer: weightRenderer,
		      				axes:{
		      					xaxis:{min:0,label:'Measurement Nr'},
		      					yaxis:{
		      						min:0,
		      						max:120,
		      						label:'Weight, kg',
		      						labelRenderer:$.jqplot.CanvasAxisLabelRenderer,
		      						
		      					}
		      				},
		  					series:[{color:'#5FAB78'}],
		  										      
		  					});

		  				var waistMeasurePlot = $.jqplot('waistMeasureDiv',myData,{
		     				//title: 'Waist Measure Data',
		      				dataRenderer: waistMeasureRenderer,
		      				axes:{
		      					xaxis:{min:0,label:'Measurement Nr'},
		      					yaxis:{min:0, max:120,label:'Waist, cm',labelRenderer:$.jqplot.CanvasAxisLabelRenderer}
		      				},
		  					series:[{color:'#5FAB78'}]
		  				});

		  				var hipMeasurePlot = $.jqplot('hipMeasureDiv',myData,{
		     				//title: 'Hips Measure Data',
		      				dataRenderer: hipMeasureRenderer,
		      				axes:{
		      					xaxis:{min:0,label:'Measurement Nr'},
		      					yaxis:{min:0, max:120,label:'Hips, cm',labelRenderer:$.jqplot.CanvasAxisLabelRenderer}
		      				},
		  					series:[{color:'#5FAB78'}]
		  				});

		  				var armMeasurePlot = $.jqplot('armMeasureDiv',myData,{
		     				//title: 'Arm Measure Data',
		      				dataRenderer: armMeasureRenderer,
		      				axes:{
		      					xaxis:{min:0,label:'Measurement Nr'},
		      					yaxis:{min:0, max:120,label:'Arms, cm',labelRenderer:$.jqplot.CanvasAxisLabelRenderer}
		      				},
		  					series:[{color:'#5FAB78'}]
		  				});



		  				

				        function getLineheight(obj) {
						    var lineheight;
						    if (obj.css('line-height') == 'normal') {
						        lineheight = obj.css('font-size');
						    } else {
						        lineheight = obj.css('line-height');
						    }
						    return parseInt(lineheight.replace('px',''));
						}

						function getTextAlign(obj) {
						    var textalign = obj.css('text-align');
						    
						    if (textalign == '-webkit-auto') {
						        textalign = 'left';
						    }

						    return textalign;
						}

						function printAtWordWrap(context, text, x, y, fitWidth, lineheight) {
						    var textArr = [];
						    fitWidth = fitWidth || 0;

						    if (fitWidth <= 0) {
						        textArr.push(text);
						    }
						    
						    var words = text.split(' ');
						    var idx = 1;
						    while (words.length > 0 && idx <= words.length) {
						        var str = words.slice(0, idx).join(' ');
						        var w = context.measureText(str).width;
						        if (w > fitWidth) {
						            if (idx == 1) {
						                idx = 2;
						            }
						            textArr.push(words.slice(0, idx - 1).join(' '));
						            words = words.splice(idx - 1);
						            idx = 1;
						        } else {
						            idx++;
						        }
						    }
						    if (words.length && idx > 0) {
						        textArr.push(words.join(' '));
						    }
						    if (context.textAlign == 'center') {
						        x += fitWidth/2;
						    }
						    if (context.textBaseline == 'middle') {
						        y -= lineheight/2;
						    } else if(context.textBaseline == 'top') {
						        y -= lineheight;
						    }
						    for (idx = textArr.length - 1; idx >= 0; idx--) {
						        var line = textArr.pop();
						        if (context.measureText(line).width > fitWidth && context.textAlign == 'center') {
						            x -= fitWidth/2;
						            context.textAlign = 'left';
						            context.fillText(line, x, y + (idx+1) * lineheight);
						            context.textAlign = 'center';
						            x += fitWidth/2;
						        } else {
						            context.fillText(line, x, y + (idx+1) * lineheight);
						        }
						    }
						}

						function findPlotSize(obj) {
						    var width = obj.width();
						    var height = obj.height();
						    var legend = obj.find('.jqplot-table-legend');
						    if (legend.position()) {
						        height = legend.position().top + legend.height();
						    }
						    obj.find('*').each(function() {
						        var offset = $(this).offset();
						        tempWidth = offset.left + $(this).width()
						        tempHeight = $(this).height()
						        if(tempWidth > width) {width = tempWidth;}
						        if(tempHeight > height) {height = tempHeight;}
						    });
						    return {width: width, height: height};
						}

						function jqplotToImg(obj) {
						    var newCanvas = document.createElement("canvas");
						    var size = findPlotSize(obj);
						    newCanvas.width = size.width;
						    newCanvas.height = size.height;
						    
						    // check for plot error
						    var baseOffset = obj.offset();
						    if (obj.find("canvas.jqplot-base-canvas").length) {
						        baseOffset = obj.find("canvas.jqplot-base-canvas").offset();
						        baseOffset.left -= parseInt(obj.css('margin-left').replace('px', ''));
						    }

						    // fix background color for pasting
						    var context = newCanvas.getContext("2d");
						    var backgroundColor = "rgba(255,255,255,1)";
						    obj.children(':first-child').parents().each(function () {
						        if ($(this).css('background-color') != 'transparent') {
						            backgroundColor = $(this).css('background-color');
						            return false;
						        }
						    });
						    context.fillStyle = backgroundColor;
						    context.fillRect(0, 0, newCanvas.width, newCanvas.height);
						    
						    // add main plot area
						    obj.find('canvas').each(function () {
						        var offset = $(this).offset();
						        newCanvas.getContext("2d").drawImage(this,
						            offset.left - baseOffset.left,
						            offset.top - baseOffset.top
						        );
						    });
						    
						    obj.find(".jqplot-series-canvas > div").each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.fillStyle = $(this).css('background-color');
						        context.fillRect(
						            offset.left - baseOffset.left - parseInt($(this).css('padding-left').replace('px', '')),
						            offset.top - baseOffset.top,
						            $(this).width() + parseInt($(this).css('padding-left').replace('px', '')) + parseInt($(this).css('padding-right').replace('px', '')),
						            $(this).height() + parseInt($(this).css('padding-top').replace('px', '')) + parseInt($(this).css('padding-bottom').replace('px', ''))
						        );
						        context.font = [$(this).css('font-style'), $(this).css('font-size'), $(this).css('font-family')].join(' ');
						        context.fillStyle = $(this).css('color');
						        context.textAlign = getTextAlign($(this));
						        var txt = $.trim($(this).html()).replace(/<br style="">/g, ' ');
						        var lineheight = getLineheight($(this));
						        printAtWordWrap(context, txt, offset.left-baseOffset.left, offset.top - baseOffset.top - parseInt($(this).css('padding-top').replace('px', '')), $(this).width(), lineheight);
						    });
						    
						    // add x-axis labels, y-axis labels, point labels
						    obj.find('div.jqplot-axis > div, div.jqplot-point-label, div.jqplot-error-message, .jqplot-data-label, div.jqplot-meterGauge-tick, div.jqplot-meterGauge-label').each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.font = [$(this).css('font-style'), $(this).css('font-size'), $(this).css('font-family')].join(' ');
						        context.fillStyle = $(this).css('color');
						        var txt = $.trim($(this).text());
						        var lineheight = getLineheight($(this));
						        printAtWordWrap(context, txt, offset.left-baseOffset.left, offset.top - baseOffset.top - 2.5, $(this).width(), lineheight);
						    });
						    
						    // add the title
						    obj.children("div.jqplot-title").each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.font = [$(this).css('font-style'), $(this).css('font-size'), $(this).css('font-family')].join(' ');
						        context.textAlign = getTextAlign($(this));
						        context.fillStyle = $(this).css('color');
						        var txt = $.trim($(this).text());
						        var lineheight = getLineheight($(this));
						        printAtWordWrap(context, txt, offset.left-baseOffset.left, offset.top - baseOffset.top, newCanvas.width - parseInt(obj.css('margin-left').replace('px', ''))- parseInt(obj.css('margin-right').replace('px', '')), lineheight);
						    });
						    
						    // add the legend
						    obj.children("table.jqplot-table-legend").each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.strokeStyle = $(this).css('border-top-color');
						        context.strokeRect(
						            offset.left - baseOffset.left,
						            offset.top - baseOffset.top,
						            $(this).width(),$(this).height()
						        );
						        context.fillStyle = $(this).css('background-color');
						        context.fillRect(
						            offset.left - baseOffset.left,
						            offset.top - baseOffset.top,
						            $(this).width(),$(this).height()
						        );
						    });
						    
						    // add the swatches
						    obj.find("div.jqplot-table-legend-swatch").each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.fillStyle = $(this).css('border-top-color');
						        context.fillRect(
						            offset.left - baseOffset.left,
						            offset.top - baseOffset.top,
						            $(this).parent().width(),$(this).parent().height()
						        );
						    });
						        
						    obj.find("td.jqplot-table-legend").each(function() {
						        var offset = $(this).offset();
						        var context = newCanvas.getContext("2d");
						        context.font = [$(this).css('font-style'), $(this).css('font-size'), $(this).css('font-family')].join(' ');
						        context.fillStyle = $(this).css('color');
						        context.textAlign = getTextAlign($(this));
						        context.textBaseline = $(this).css('vertical-align');
						        var txt = $.trim($(this).text());
						        var lineheight = getLineheight($(this));
						        printAtWordWrap(context, txt, offset.left-baseOffset.left, offset.top - baseOffset.top + parseInt($(this).css('padding-top').replace('px','')), $(this).width(), lineheight);
						    });

						    // then convert the image to base64 format
						    return newCanvas.toDataURL("image/png");
						    
						}
						
						// usage
						var weightIMG = jqplotToImg($('#weightDiv'));
						var waistIMG = jqplotToImg($('#waistMeasureDiv'));
						var hipIMG = jqplotToImg($('#hipMeasureDiv'));
						var armIMG = jqplotToImg($('#armMeasureDiv'));


						/*$('#weightIMG').attr('href', weightIMG);
						$('#waistIMG').attr('href', waistIMG);
						$('#hipIMG').attr('href', hipIMG);
						$('#armIMG').attr('href', armIMG);*/
						
						

						$('#weightIMGData').attr('value', weightIMG);
						$('#waistIMGData').attr('value', waistIMG);
						$('#hipIMGData').attr('value', hipIMG);
						$('#armIMGData').attr('value', armIMG);
		  			}	
	  				

			    }


			     
			 });

			$("#addRow").click(function(){ 
				jQuery("#progress").jqGrid('editGridRow',"new",{
					reloadAfterSubmit:true,
					closeAfterAdd:true,
					//afterSubmit:errorMessage
				}); 
			});

			$("#editRow").click(function(){ 
				var gr = jQuery("#progress").jqGrid('getGridParam','selrow'); 
				if( gr != null ) 
					jQuery("#progress").jqGrid('editGridRow',gr,{reloadAfterSubmit:true,closeAfterEdit:true}); 
				else 
				{
					//alert("Please Select Row"); 
					$("#dialogSel").html('Please select row!');
					$("#dialogSel").dialog('open');
				}
			});


						
			$("#delRow").click(function(){ 
				
				var gr0 = jQuery("#progress").jqGrid('getGridParam','selrow'); 

				if( gr0 != null ){
					//alert(gr.length);
					var gr = jQuery("#progress").jqGrid('getGridParam','selarrrow'); 
					jQuery("#progress").jqGrid('delGridRow',gr.toString(),{reloadAfterSubmit:true});					

				}
				else{
					//alert("Please Select Row"); 
					$("#dialogSel").html('Please select row!');
					$("#dialogSel").dialog('open');
				}
			});

			jQuery("#progress").jqGrid('navGrid','#pgProgress',{edit:false,add:false,del:false,search:false,refresh:true}); 
			
			jQuery("#progress").jqGrid('filterToolbar',{searchOnEnter:false});

			$("#toggleSearchProgress").click(function(){ 
				progressGrid[0].toggleToolbar(); 
			});

			$("#clearSearchProgress").click(function(){ 
				progressGrid[0].clearToolbar();
			});


		});

	</script>


<style type="text/css">

	body{
		font-size: 12px;

	}

	div#buttonsDiv{
		margin: 10px 0px;
		font-size: 12px;

	}

	div.chartsDiv{
		margin: 20px 0px 10px 20px;
		height:400px;
		width:500px;	
		text-align: left;
	}

	.right{
		position:absolute;
		left: 600px;

	}

	.left{
		float:left;
	}

	div.clr{
		clear:both;
	}

	#submitPdf{margin-left:4px;}
</style>

</head>
<body>



<h2>Patient: <?php print $ffirstName;?>&nbsp;<?php print $llastName;?></h2>


<div id="dialogSel"></div>


<div>
	<table id="progress"></table>
	<div id="pgProgress"></div>
	<div id="filterToolbar" style="margin-left:30%;display:none">Search Progress</div>
</div>

<div id="buttonsDiv" style="float:left;">
    <input type="button" class="ui-button" value="Add Record" id="addRow" />
    <input type="button" class="ui-button" value="Edit Record" id="editRow" />
    <input type="button" class="ui-button" value="Delete Record" id="delRow"/>    
    <input type="button" class="ui-button" value="Toggle Search Panel" id="toggleSearchProgress"/>    
    <input type="button" class="ui-button" value="Clear Search" id="clearSearchProgress"/>
</div>

<!--<div class="clr"></div>-->

<div class="buttonsDiv">
	<form action="progressPDF.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="patientId" id="patientId" value="" />
		<input type="hidden" name="weightIMGData" id="weightIMGData" value="" />
		<input type="hidden" name="waistIMGData" id="waistIMGData" value="" />
		<input type="hidden" name="hipIMGData" id="hipIMGData" value="" />
		<input type="hidden" name="armIMGData" id="armIMGData" value="" />
		<input type="submit" class="ui-button" id="submitPdf" value="Export to PDF" />
	</form>
</div>


<div class="clr"></div>

<div class="left">
	<div id="weightDiv" class="chartsDiv"></div>	
</div>

<div class="right">
	<div id="waistMeasureDiv" class="chartsDiv"></div>	
</div>	

<div class="clr"></div>

<div class="left">
	<div id="hipMeasureDiv" class="chartsDiv"></div>
</div>

<div class="right">
	<div id="armMeasureDiv" class="chartsDiv"></div>
</div>
<div class="clr"></div>



</body>
</html>