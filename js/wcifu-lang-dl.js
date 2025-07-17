/**
 * WooCommerce Frontend display of product documents
 * 
 * @package WC_Ifu_Docs
 */



  function setEngButtonUrl(btn) {
    var folder = btn.dataset.folder;
    var fname = btn.dataset.fname;
    var dlink = folder + '/' + fname;
    btn.setAttribute("href", dlink);
    //console.log(dlink);
  }


  function setLangButtonUrl(sel) {
    var docId = sel.dataset.id;
    var btn = document.getElementById(docId);
    var folder = btn.dataset.folder;
    var opt = sel.options[sel.selectedIndex];
    var fname = opt.dataset.file;
    var dlink = folder + '/' + fname;
    document.getElementById(docId).setAttribute("href", dlink);
    //console.log(dlink);
  }
