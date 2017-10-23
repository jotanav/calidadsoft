$(function(){ 


  $('#numtit').autocomplete({
    source:'../CDatos/BuscarTitulo/buscartit.php',
    minLength:2,
    select:function(evt, ui)
    {
      // when a zipcode is selected, populate related fields in this form

      this.form.txt_numtit.value = ui.item.value;
      this.form.id_titulo.value = ui.item.idtit;
      this.form.txt_nomprop.value = ui.item.nomprop;
	  this.form.txt_mesanio.value = ui.item.mesanio;

    }
  });
  
  $('#nomprop').autocomplete({
    source:'../CDatos/BuscarTitulo/buscarnom.php',
    minLength:2,
    select:function(evt, ui)
    {
      // when a zipcode is selected, populate related fields in this form

      this.form.txt_nomprop.value = ui.item.value;
      this.form.id_titulo.value = ui.item.idtit;
      this.form.txt_numtit.value = ui.item.numero;
	  this.form.txt_mesanio.value = ui.item.mesanio;

    }
  });


});