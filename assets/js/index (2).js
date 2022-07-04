
var pageWidth = 595;
  var pageHeight = 842;
  var y=500;
  var pageMargin = 50;
  var top_height = 10;

  pageWidth -= pageMargin * 2;
  pageHeight -= top_height * 2;

  var cellMargin = 5;
  var cellWidth = 250;
  var cellHeight = 60;

  var startX = pageMargin;
  var startY = top_height;    
function setCookie(cname, cvalue, exdays) {
    var domain = window.location.hostname;
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires+"; path=/; domain="+domain;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function round (value, precision, mode) {
  var m, f, isHalf, sgn // helper variables
  // making sure precision is integer
  precision |= 0
  m = Math.pow(10, precision)
  value *= m
  // sign of the number
  sgn = (value > 0) | -(value < 0)
  isHalf = value % 1 === 0.5 * sgn
  f = Math.floor(value)

  if (isHalf) {
    switch (mode) {
      case 'PHP_ROUND_HALF_DOWN':
      // rounds .5 toward zero
        value = f + (sgn < 0)
        break
      case 'PHP_ROUND_HALF_EVEN':
      // rouds .5 towards the next even integer
        value = f + (f % 2 * sgn)
        break
      case 'PHP_ROUND_HALF_ODD':
      // rounds .5 towards the next odd integer
        value = f + !(f % 2)
        break
      default:
      // rounds .5 away from zero
        value = f + (sgn > 0)
    }
  }

  return (isHalf ? value : Math.round(value)) / m
}

async function supportsWebp() {
  if (!self.createImageBitmap) return false;
  
  const webpData = 'data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=';
  const blob = await fetch(webpData).then(r => r.blob());
  return createImageBitmap(blob).then(() => true, () => false);
}

function generate() {

  var doc = new jsPDF('p', 'pt');

  var res = doc.autoTableHtmlToJson(document.getElementById("basic-table"));
  doc.autoTable(res.columns, res.data, {margin: {top: 80}});

  var header = function(data) {
    doc.setFontSize(18);
    doc.setTextColor(40);
    doc.setFontStyle('normal');
    //doc.addImage(headerImgData, 'JPEG', data.settings.margin.left, 20, 50, 50);
    doc.text("Testing Report", data.settings.margin.center, 50);
  };

  var options = {
    beforePageContent: header,
    margin: {
      top: 80
    },
    startY: doc.autoTableEndPosY() + 20
  };

  doc.autoTable(res.columns, res.data, options);

  doc.save("table.pdf");
}

var comapnyJSON={
  CompanyName:'ABCD TECHONOLOGIES',
  CompanyGSTIN:'37B76C238B7E1Z5',
  CompanyState:'KERALA (09)',
  CompanyPAN:'B76C238B7E',
  CompanyAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
  CompanyAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
  CompanyAddressLine3:'COCHIN',
  PIN: '683584',
  companyEmail:'xyz@gmail.com',
  companyPhno:'+918189457845',
};

var customer_BillingInfoJSON={
  CustomerName:'Jino Shaji',
  CustomerGSTIN:'37B76C238B7E1Z5',
  CustomerState:'KERALA (09)',
  CustomerPAN:'B76C238B7E',
  CustomerAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
  CustomerAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
  CustomerAddressLine3:'COCHIN',
  PIN: '683584',
  CustomerEmail:'abcd@gmail.com',
  CustomerPhno:'+918189457845',
};


var customer_ShippingInfoJSON={
  CustomerName:'Jino Shaji',
  CustomerGSTIN:'37B76C238B7E1Z5',
  CustomerState:'KERALA (09)',
  CustomerPAN:'B76C238B7E',
  CustomerAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
  CustomerAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
  CustomerAddressLine3:'COCHIN',
  PIN: '683584',
  CustomerEmail:'abcd@gmail.com',
  CustomerPhno:'+918189457845',
};


var invoiceJSON={
  InvoiceNo:'INV-120152',
  InvoiceDate:'03-12-2017',
  RefNo:'REF-78445',
  TotalAmnt:'Rs.1,24,200',
  SubTotalAmnt:'Rs.1,04,200',
  TotalGST:'Rs.2,0000',
  TotalCGST:'Rs.1,0000',
  TotalSGST:'Rs.1,0000',
  TotalIGST:'Rs.0',
  TotalCESS:'Rs.0',
}

// var company_logo = {
//   src1:'data:image/png;base64,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',

//   w: 80,
//   h: 50
// };

var fontSizes={
  HeadTitleFontSize:18,
  Head2TitleFontSize:16,
  TitleFontSize:14,
  SubTitleFontSize:12,
  NormalFontSize:10,
  SmallFontSize:8
};
 
var lineSpacing={
  NormalSpacing:12,
};

function generate_cutomPDF(company_logo,rows,sum,facility_name,initiation_code,lab_id,mod,patient_name,receipt_number,facility_state,facility_country,date) {
  
    var doc = new jsPDF('p', 'pt');
  
    var rightStartCol1=400;
    var rightStartCol2=480;
    var comapnyJSON={
      CompanyName:facility_name,
      CompanyGSTIN:'37B76C238B7E1Z5',
      CompanyState:'KERALA (09)',
      CompanyPAN:'B76C238B7E',
      CompanyAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
      CompanyAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
      CompanyAddressLine3:'COCHIN',
      PIN: '683584',
      companyEmail:'xyz@gmail.com',
      companyPhno:'+918189457845',
    };

    // var InitialstartX=40;
    // var startX=40;
    var InitialstartY=50;
    // var startY=0;

    var lineHeights=12;

    var res = doc.autoTableHtmlToJson(document.getElementById("basic-table"));
      res = doc.autoTableHtmlToJson(document.getElementById("tblInvoiceItemsList"));
    
    doc.setFontSize(fontSizes.TitleFontSize);
    doc.setFont('times');
    doc.setFontType('bold');
    
    //pdf.addImage(agency_logo.src, 'PNG', logo_sizes.centered_x, _y, logo_sizes.w, logo_sizes.h);
    // doc.addImage(company_logo.src, 'JPEG', startX + 450,startY, company_logo.w,company_logo.h);
    doc.addImage(company_logo.src, 'PNG', startX,startY+=50, company_logo.w,company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.textAlign("OneHealth Issues Global Limited", {align: "right"}, startX + 470, startY+=15+company_logo.h);

    doc.textAlign(comapnyJSON.CompanyName, {align: "left"}, startX, startY+=15+company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    
    doc.setFontType('bold');
    doc.textAlign("STATE: ", {align: "left"}, startX, startY+=lineSpacing.NormalSpacing);
    doc.setFontType('normal');
    doc.textAlign("   " + facility_state + " " + facility_country, {align: "left"}, 80, startY);

    doc.setFontType('bold');
    doc.textAlign("DATE: ", {align: "left"}, startX, startY+=lineSpacing.NormalSpacing);
    doc.setFontType('normal');
    doc.textAlign("   " + date, {align: "left"}, 80, startY);


   var tempY=InitialstartY;
  
    doc.setFontSize(fontSizes.Head2TitleFontSize);
    doc.setFontType('bold');
    doc.textAlign("INVOICE FOR MEDICAL SERVICES", {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+10);

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('italic');
    doc.textAlign("We Confirm Receipt Of Payment For: ", {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+16);
     

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.textAlign(patient_name, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+22);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');

    //-------Customer Info Billing---------------------
   var startBilling=startY;
    
    //-------Customer Info Shipping---------------------
    var rightcol1=340;
    var rightcol2=400;

    startY=startBilling;    


    var header = function(data) {
      doc.setFontSize(8);
      doc.setTextColor(40);
      doc.setFontStyle('normal');
     // doc.textAlign("TAX INVOICE", {align: "center"}, data.settings.margin.left, 50);
 
      //doc.addImage(headerImgData, 'JPEG', data.settings.margin.left, 20, 50, 50);
     // doc.text("Testing Report", 110, 50);
    };
   // doc.autoTable(res.columns, res.data, {margin: {top:  startY+=30}});
   doc.setFontSize(8);
   doc.setFontStyle('normal');
   
    var options = {
      beforePageContent: header,
      margin: {
        top: 50 
      },
      styles: {
        overflow: 'linebreak',
        fontSize: 10,
        rowHeight: 'auto',
        columnWidth: 150
      },
      startY: startY+=50
    };
  var columns = [
    {title: "#" ,dataKey : "i",width : 500},
    {title: "TEST ID" ,dataKey : "testid",width : 500},
    {title: "TEST NAME" ,dataKey : "testname",width : 500},
    {title: "TEST COST" ,dataKey : "testcost",width : 500}
  ];

  

    doc.autoTable(columns, rows, options);   //From dynamic data.
  // doc.autoTable(res.columns, res.data, options); //From htmlTable
  


  //-------Invoice Footer---------------------
  var rightcol1=340;
  var rightcol2=430;

  startY=doc.autoTableEndPosY()+30;
  doc.setFontSize(fontSizes.NormalFontSize);
  
  doc.setFontType('bold');
  doc.textAlign("Pathology Number:", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.textAlign("  " + lab_id, {align: "left"}, rightcol2, startY);
  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('bold');
  doc.textAlign("Sub Total: ", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign(sum, {align: "left"},rightcol2, startY);
  

  doc.setFontType('bold');
  doc.textAlign("Initiation Code", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign("  " + initiation_code, {align: "left"},rightcol2, startY);
  
  doc.setFontType('bold');
  doc.textAlign("Mode Of Payment.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign( "      " + mod, {align: "left"},rightcol2, startY);
  

  doc.setFontType('bold');
  doc.textAlign("Receipt Number: ", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign( "   " + receipt_number, {align: "left"},rightcol2, startY);
  
 //  doc.setFontType('bold');
 //  doc.textAlign("Total GST Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
 //  doc.setFontType('normal');
 // // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
 //  doc.textAlign(invoiceJSON.TotalGST, {align: "left"},rightcol2, startY);
  

 //  doc.setFontType('bold');
 //  doc.textAlign("Grand Total Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.setFontType('bold');
  doc.setFontSize(fontSizes.SubTitleFontSize);
  doc.textAlign('For '+facility_name, {align: "center"},rightcol2, startY+=lineSpacing.NormalSpacing+50);
  // doc.textAlign('Authorised Signatory', {align: "center"},rightcol2, startY+=lineSpacing.NormalSpacing+50);
 
  
    // doc.save("invoice.pdf");
    return doc.output();
}


function generate_cutomPDFResult(company_logo,rows,facility_name,initiation_code,lab_id,patient_name,facility_state,facility_country,date,receptionist,teller,clerk,lab_three,lab_two,supervisor,pathologist,images) {
  
    var doc = new jsPDF('p', 'pt','a4');
  
    var rightStartCol1=400;
    var rightStartCol2=480;
    var comapnyJSON={
      CompanyName:facility_name,
      CompanyGSTIN:'37B76C238B7E1Z5',
      CompanyState:'KERALA (09)',
      CompanyPAN:'B76C238B7E',
      CompanyAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
      CompanyAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
      CompanyAddressLine3:'COCHIN',
      PIN: '683584',
      companyEmail:'xyz@gmail.com',
      companyPhno:'+918189457845',
    };

    // var InitialstartX=40;
    // var startX=40;
    var InitialstartY=50;
    // var startY=0;

    // var lineHeights=12;

    var res = doc.autoTableHtmlToJson(document.getElementById("basic-table"));
      res = doc.autoTableHtmlToJson(document.getElementById("tblInvoiceItemsList"));
    
    doc.setFontSize(fontSizes.TitleFontSize);
    doc.setFont('times');
    doc.setFontType('bold');
    
    //pdf.addImage(agency_logo.src, 'PNG', logo_sizes.centered_x, _y, logo_sizes.w, logo_sizes.h);
    doc.addImage(company_logo.src, 'PNG', startX,startY+=50, company_logo.w,company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.textAlign("OneHealth Issues Global Limited", {align: "right"}, startX + 470, startY+=15+company_logo.h);

    doc.textAlign(comapnyJSON.CompanyName, {align: "left"}, startX, startY+=15+company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    
    doc.setFontType('bold');
    doc.textAlign("STATE: ", {align: "left"}, startX, startY+=lineSpacing.NormalSpacing);
    doc.setFontType('normal');
    doc.textAlign("   " + facility_state + " " + facility_country, {align: "left"}, 80, startY);

    doc.setFontType('bold');
    doc.textAlign("DATE: ", {align: "left"}, startX, startY+=lineSpacing.NormalSpacing);
    doc.setFontType('normal');
    doc.textAlign("   " + date, {align: "left"}, 80, startY);


   var tempY=InitialstartY;
  
    doc.setFontSize(fontSizes.Head2TitleFontSize);
    doc.setFontType('bold');
    doc.textAlign("Laboratory Test Results", {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+10);
     

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.textAlign(patient_name, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+22);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');

    //-------Customer Info Billing---------------------
   var startBilling=startY;
    
    //-------Customer Info Shipping---------------------
    var rightcol1=340;
    var rightcol2=400;

    startY=startBilling;    


    var header = function(data) {
      doc.setFontSize(8);
      doc.setTextColor(40);
      doc.setFontStyle('normal');
     // doc.textAlign("TAX INVOICE", {align: "center"}, data.settings.margin.left, 50);
 
      //doc.addImage(headerImgData, 'JPEG', data.settings.margin.left, 20, 50, 50);
     // doc.text("Testing Report", 110, 50);
    };
   // doc.autoTable(res.columns, res.data, {margin: {top:  startY+=30}});
   doc.setFontSize(8);
   doc.setFontStyle('normal');
   
    var options = {
      beforePageContent: header,
      margin: {
        top: 50 
      },
      styles: {
        overflow: 'linebreak',
        fontSize: 10,
        rowHeight: 'auto',
        columnWidth: 80
      },
      startY: startY+=50
    };
  var columns = [
    {title: "#" ,dataKey : "i",width : 500},
    {title: "TEST ID" ,dataKey : "testid",width : 500},
    {title: "TEST NAME" ,dataKey : "testname",width : 500},
    {title: "TEST RESULT" ,dataKey : "testresult",width : 500},
    {title: "REFERENCE RANGE" ,dataKey : "range",width : 500},
    {title: "FLAG" ,dataKey : "flag",width : 500}
  ];

  

    doc.autoTable(columns, rows, options);   //From dynamic data.
  // doc.autoTable(res.columns, res.data, options); //From htmlTable

  // for(var i = 0; i < images.length; i++){
  //   doc.setFontSize(fontSizes.SubTitleFontSize);
  //   doc.setFontType('bold');
  //   doc.textAlign(images[i].test_name, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10)
  //   doc.addImage(images[i].src, images[i].type, startX,startY+=lineSpacing.NormalSpacing+100, 200,200);
  //   addPage(doc);
  // }

  addNewPage(doc);

  doc.setFontSize(fontSizes.SubTitleFontSize);
  doc.setFontType('bold');
  doc.textAlign("Contributors", {align: "left"}, startX, startY);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Receptionist: " +receptionist, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Teller: " + teller, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Clerk: " + clerk, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Laboratory Officer 3: " + lab_three, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Laboratory Officer Two: " + lab_two, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Supervisor: " + teller, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);

  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('normal');
  doc.textAlign("Consultant Pathologist: " + pathologist, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+10);


  doc.setFontType('bold');
  doc.setFontSize(fontSizes.SubTitleFontSize);
  doc.textAlign('For '+facility_name, {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+10);


  //-------Invoice Footer---------------------
  var rightcol1=340;
  var rightcol2=430;

  startY=doc.autoTableEndPosY()+30;
  doc.setFontSize(fontSizes.NormalFontSize);
  
  doc.setFontType('bold');
  doc.textAlign("Pathology Number:", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.textAlign("  " + lab_id, {align: "left"}, rightcol2, startY);
  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('bold');
  
  

  doc.setFontType('bold');
  doc.textAlign("Initiation Code", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign("  " + initiation_code, {align: "left"},rightcol2, startY);

  for(var i = 0; i < rows.length; i++){
    addNewPage(doc)
    var index = rows[i].i;
    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.textAlign(rows[i].testname + " 's Images: ", {align: "center"}, startX , startY);
    
    for(var j = 0; j < images.length; j++){
      var image_index = images[j].i;
      if(image_index == index){
        // console.log("Image: " + image_index);
        if(i == 0){
          doc.addImage(images[j].src, images[j].type, startX,startY+=lineSpacing.NormalSpacing+200, 300,200);
        }else{
          doc.addImage(images[j].src, images[j].type, startX,startY+=lineSpacing.NormalSpacing +200, 300,200);
        }
      }
       
    }
  }

  
  
  
 //  doc.setFontType('bold');
 //  doc.textAlign("Total GST Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
 //  doc.setFontType('normal');
 // // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
 //  doc.textAlign(invoiceJSON.TotalGST, {align: "left"},rightcol2, startY);
  

 //  doc.setFontType('bold');
 //  doc.textAlign("Grand Total Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  
  // doc.textAlign('Authorised Signatory', {align: "center"},rightcol2, startY+=lineSpacing.NormalSpacing+50);
 
  
    // doc.save("invoice.pdf");
    return doc.output();
}

function generate_cutomPDFClinics(company_logo,sum,facility_name,hospital_num,mod,patient_name,receipt_number,facility_state,facility_country) {
  
    var doc = new jsPDF('p', 'pt');
  
    var rightStartCol1=400;
    var rightStartCol2=480;
    var comapnyJSON={
      CompanyName:facility_name,
      CompanyGSTIN:'37B76C238B7E1Z5',
      CompanyState:'KERALA (09)',
      CompanyPAN:'B76C238B7E',
      CompanyAddressLine1:'ABCDEFGD HOUSE,IX/642-D',
      CompanyAddressLine2:'ABCDEFGD P.O., NEDUMBASSERY',
      CompanyAddressLine3:'COCHIN',
      PIN: '683584',
      companyEmail:'xyz@gmail.com',
      companyPhno:'+918189457845',
    };

    // var InitialstartX=40;
    // var startX=40;
    var InitialstartY=50;
    // var startY=0;

    var lineHeights=12;

    // var res = doc.autoTableHtmlToJson(document.getElementById("basic-table"));
    //   res = doc.autoTableHtmlToJson(document.getElementById("tblInvoiceItemsList"));
    
    doc.setFontSize(fontSizes.TitleFontSize);
    doc.setFont('times');
    doc.setFontType('bold');
    
    //pdf.addImage(agency_logo.src, 'PNG', logo_sizes.centered_x, _y, logo_sizes.w, logo_sizes.h);
    doc.addImage(company_logo.src, 'PNG', startX,startY+=50, company_logo.w,company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.textAlign("OneHealth Issues Global Limited", {align: "right"}, startX + 470, startY+=15+company_logo.h);


    doc.textAlign(comapnyJSON.CompanyName, {align: "left"}, startX, startY+=15+company_logo.h);
    doc.setFontSize(fontSizes.SubTitleFontSize);
    
    doc.setFontType('bold');
    doc.textAlign("STATE: ", {align: "left"}, startX, startY+=lineSpacing.NormalSpacing);
    doc.setFontType('normal');
    doc.textAlign("   " + facility_state + " " + facility_country, {align: "left"}, 80, startY);


   var tempY=InitialstartY;
  
    doc.setFontSize(fontSizes.Head2TitleFontSize);
    doc.setFontType('bold');
    doc.textAlign("INVOICE FOR MEDICAL SERVICES", {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+10);

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('italic');
    doc.textAlign("We Confirm Receipt Of Payment For Hospital Registration By: ", {align: "center"}, startX, startY+=lineSpacing.NormalSpacing+16);
     

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.textAlign(patient_name, {align: "left"}, startX, startY+=lineSpacing.NormalSpacing+22);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');

    //-------Customer Info Billing---------------------
   var startBilling=startY;
    
    //-------Customer Info Shipping---------------------
    var rightcol1=340;
    var rightcol2=400;

    startY=startBilling;    


    var header = function(data) {
      doc.setFontSize(8);
      doc.setTextColor(40);
      doc.setFontStyle('normal');
     // doc.textAlign("TAX INVOICE", {align: "center"}, data.settings.margin.left, 50);
 
      //doc.addImage(headerImgData, 'JPEG', data.settings.margin.left, 20, 50, 50);
     // doc.text("Testing Report", 110, 50);
    };
   // doc.autoTable(res.columns, res.data, {margin: {top:  startY+=30}});
   doc.setFontSize(8);
   doc.setFontStyle('normal');
   
    var options = {
      beforePageContent: header,
      margin: {
        top: 50 
      },
      styles: {
        overflow: 'linebreak',
        fontSize: 10,
        rowHeight: 'auto',
        columnWidth: 150
      },
      startY: startY+=50
    };
  var columns = [
    {title: "#" ,dataKey : "i",width : 500},
    {title: "TEST ID" ,dataKey : "testid",width : 500},
    {title: "TEST NAME" ,dataKey : "testname",width : 500},
    {title: "TEST COST" ,dataKey : "testcost",width : 500}
  ];

  

    // doc.autoTable(columns, rows, options);   //From dynamic data.
  // doc.autoTable(res.columns, res.data, options); //From htmlTable
  


  //-------Invoice Footer---------------------
  var rightcol1=340;
  var rightcol2=430;

  // startY=doc.autoTableEndPosY()+30;
  doc.setFontSize(fontSizes.NormalFontSize);
  
  doc.setFontType('bold');
  doc.textAlign("Hospital Number:", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.textAlign("  " + hospital_num, {align: "left"}, rightcol2, startY);
  doc.setFontSize(fontSizes.NormalFontSize);
  doc.setFontType('bold');
  doc.textAlign("Sub Total: ", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign(sum, {align: "left"},rightcol2, startY);
  

  doc.setFontType('bold');
  doc.textAlign("Mode Of Payment.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign( "      " + mod, {align: "left"},rightcol2, startY);
  

  doc.setFontType('bold');
  doc.textAlign("Receipt Number: ", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.textAlign( "   " + receipt_number, {align: "left"},rightcol2, startY);
  
 //  doc.setFontType('bold');
 //  doc.textAlign("Total GST Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
 //  doc.setFontType('normal');
 // // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
 //  doc.textAlign(invoiceJSON.TotalGST, {align: "left"},rightcol2, startY);
  

 //  doc.setFontType('bold');
 //  doc.textAlign("Grand Total Rs.", {align: "left"}, rightcol1, startY+=lineSpacing.NormalSpacing);
  doc.setFontType('normal');
 // var w = doc.getStringUnitWidth('GSTIN') * NormalFontSize;
  doc.setFontType('bold');
  doc.setFontSize(fontSizes.SubTitleFontSize);
  doc.textAlign('For '+facility_name, {align: "center"},rightcol2, startY+=lineSpacing.NormalSpacing+50);
  // doc.textAlign('Authorised Signatory', {align: "center"},rightcol2, startY+=lineSpacing.NormalSpacing+50);
 
  
    // doc.save("invoice.pdf");
    return doc.output();
}


function addPage (doc) {
  // console.log(startY)
  if (startY >= pageHeight)
{
  doc.addPage();
  startY = top_height;  // Restart height position
}
}


function addNewPage (doc) {
  doc.addPage();
  startY = top_height; 
}