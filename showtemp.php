<!DOCTYPE html>
<html lang="en">
   <head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Belle 2 Online DQM histograms</title>
   <link rel="shortcut icon" href="https://root.cern/js/latest/img/RootIcon.ico"/>
   <script src="https://root.cern/js/latest/scripts/JSRootCore.js?jq2d&onload=createGUI" type="text/javascript"></script>
   <script type='text/javascript'>
   var h;
function createGUI() {
  h = new JSROOT.HierarchyPainter("example", "myTreeDiv");
  // configure 'simple' in provided <dev> element
  // one also can specify "grid2x2" or "flex" or "tabs"
  h.SetDisplay("tabs", "myMainDiv");
  // open file and display element
  <?php
      $file=isset($_GET['file'])?$_GET['file']:"x";
  echo   "displayFile('$file');"
    ?>

      }

function CreateLegendEntry(obj, lbl, opt) {
  var entry = JSROOT.Create("TLegendEntry");
  entry.fObject = obj;
  entry.fLabel = lbl;
  entry.fOption = opt;
  return entry;
   }

     function displayFile(fname){
       h.clear(true);
       var e = document.getElementById("file");
       var filepath = e.options[e.selectedIndex].value;
       if (fname.length>2) filepath=fname;
       JSROOT.progress("Opening " + filepath + " ...");
       console.log('fname =' + fname + '  path=' + filepath)
       //JSROOT.OpenFile(filepath, function(file) {
       h.OpenRootFile(filepath, function(file) {
         for (i = 1; i <= 6; i++) {
           var x=["min","max","ave"];
           var opts=["hist","hist","hist"];
           var txt = "";
           var mgraph = JSROOT.CreateTMultiGraph();
           mgraph.fTitle = "Drawing " + i;
           var leg = JSROOT.Create("TLegend");
           leg.fName = "Legend";
           JSROOT.extend(leg, { fX1NDC: 0.2, fY1NDC:0.75, fX2NDC: 0.6, fY2NDC:0.9 });
           mgraph.fMinimum = 0;
           mgraph.fMaximum = 50;
           var ncol=0;
           x.forEach(function(value) {
              var txt = 'gr_feb_t1_' + value + '_sec_' + i + ';1'; 
                h.display(txt,opts[ncol] );                
                return;
                file.ReadObject(txt, function(graph) {               
                console.log(txt);
                if (graph == null) {
                    console.log("graph is null");
//                  return;
                }
//              graph.fLineColor = ncol++;
//              graph.fMarkerSize = 2;
                leg.fPrimitives.Add(CreateLegendEntry(graph, value ,"L" ));
                if (graph !=null) mgraph.fGraphs.Add(graph);
                if (ncol==2) {
                  h.display(mgraph );
                 //JSROOT.redraw('object_draw', mgraph, "", function() { drawing_ready = true; });
                }
                ncol++;   
              });         
            
             });

           }
         h.toggleOpenState.bind(h,true);
       });
    }
   </script>

</head>
<body>

  <div style="top: 0; bottom:0; left: 0; right: 0; position: absolute; ">
  <div id="headerDiv" style="width: 250px; height: 100%; float:left">
<h1>Belle II ARICH DQM</h1>

<select name='file'  id='file'>
<?php

 $filelist = array_reverse(glob("slcdata/temperature/*.root"));
 //$filelist = glob("*.root");
  $files="";

  foreach ( $filelist as $fname){
    $selected="";
    if ($file=="x") {
       $file=$fname;
    } else {
       if ($file==$fname ) $selected="selected";
    }
    $bname=basename($fname);
      $year = substr($bname, 8 , 2);
      $month = substr($bname, 10 , 2);
      $day   = substr($bname, 12 , 2);

      echo "<option value='$fname' $selected >" . substr($bname,0,7) . ":$year/$month/$day</option>\n";
  }

?>
</select>
<button   onclick="displayFile('x')" >Display</button>

  <div id="myTreeDiv" style="width: 250px; height: calc(100% -100px); float:left; border:1px solid red;"></div>
</div>
  <div id="myMainDiv" style="width: calc(100% - 250px); height: 100% ; float:right"></div>
  </div>
</body>
</html>
