<script>
    $(document).ready(function () {
      
       $("#alertz").show().delay(2000).fadeOut();
       $("#alertz2").show().delay(2000).fadeOut();
       $("#alertz3").show().delay(2000).fadeOut();
       $("#alertz4").show().delay(2000).fadeOut();
       $("#alertz5").show().delay(2000).fadeOut();
       $("#alertz6").show().delay(2000).fadeOut();
       $("#alertz7").show().delay(2000).fadeOut();
       $("#alertz8").show().delay(2000).fadeOut();
       $("#alertz9").show().delay(2000).fadeOut();
      //data pendidikan
      $(document).on('click', '.btn-add-hm', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls form:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm')
              .removeClass('btn-add-hm').addClass('btn-remove-hm')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

      //data pendidikan non
      $(document).on('click', '.btn-add-hm2', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls2 form:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm2')
              .removeClass('btn-add-hm2').addClass('btn-remove-hm2')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm2', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

      //data kegiatan organisasi
      $(document).on('click', '.btn-add-hm3', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls3 form:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm3')
              .removeClass('btn-add-hm3').addClass('btn-remove-hm3')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm3', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

       //data anak
      $(document).on('click', '.btn-add-hm4', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls4 form:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm4')
              .removeClass('btn-add-hm4').addClass('btn-remove-hm4')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm4', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

      //data orang terdekat 

      $(document).on('click', '.btn-add-hm5', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls5 form:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm5')
              .removeClass('btn-add-hm5').addClass('btn-remove-hm5')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm5', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

      // Perjalan Dinas

      $(document).on('click', '.btn-add-hm6', function(e)
      { 
          e.preventDefault();

          var controlForm = $('.controls6:first'),
              currentEntry = $(this).parents('.entry:first'),
              newEntry = $(currentEntry.clone()).appendTo(controlForm);

          newEntry.find('input').val('');
          controlForm.find('.entry:not(:last) .btn-add-hm6')
              .removeClass('btn-add-hm6').addClass('btn-remove-hm6')
              .removeClass('btn-success').addClass('btn-danger')
              .html('<span class="glyphicon glyphicon-minus"></span>');
      }).on('click', '.btn-remove-hm6', function(e)
      {
          $(this).parents('.entry:first').remove();

          e.preventDefault();
          return false;
      });

      //end
        if($('#status').val() == '5' || $('#status').val() == '6'){
          $('#vendor').show();
          $("#Vendor").prop('required',true);
          $('#golout').show();
          $('#gol').hide();
        }
        else{
          $('#vendor').hide();
          $("#Vendor").prop('required',false);
          $('#gol').show();
          $('#golout').hide();
        } 

// FOR ADD CUTI 
    $('#date2').change(function() {

    var date1 = $('#date1').val();
    var date2 = $('#date2').val();
    var sisa_cuti = $('#sisa_cuti').val();
    // alert(sisa_cuti);
    var token = '{{Session::token()}}';
    var selisihtanggal = '{{ url('selisihtanggal') }}';

    $.ajax({
      method: 'POST',
      url : selisihtanggal,
      data : { date1:date1, date2:date2, _token : token},
    }).done(function (msg) {
      console.log(msg['hasilcuti']);

       $('#rencanacuti').val(msg['hasilcuti']);
       var coba = parseInt(sisa_cuti) - parseInt((msg['hasilcuti']));
       // alert(coba);
       $('#sisacuti16').val(coba);

    });

  });    

    $('#gaji1').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
                          });
    $("#gaji1").keyup(function() {
      var clone = $(this).val();
      var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
      $("#gaji2").val(cloned);
    });

    $('#tunj_tmr1').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
                          });
    $("#tunj_tmr1").keyup(function() {
      var clone = $(this).val();
      var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
      $("#tunj_tmr2").val(cloned);
    });

    $('#tunj_jab1').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
                          });
    $("#tunj_jab1").keyup(function() {
      var clone = $(this).val();
      var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
      $("#tunj_jab2").val(cloned);
    });

    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

    //kesehatan
        $('#jklaim').change(function() {
          var sisa = $('#sisa').val();
          var klaim = $('#jklaim_1').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Klaim tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jklaim').val(0);
              $('#jklaim_1').val(0);
            }
          }
        });
        $('#jsetuju').change(function() {
          var sisa = $('#sisa').val();
          var klaim = $('#jsetuju_1').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Disetujui tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jsetuju').val(0);
              $('#jsetuju_1').val(0);
            }
          }
        });

        $('#jklaim2').change(function() {
          var sisa = $('#sisa2').val();
          var klaim = $('#jklaim_2').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Klaim tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jklaim2').val(0);
              $('#jklaim_2').val(0);
            }
          }
        });
        $('#jsetuju2').change(function() {
          var sisa = $('#sisa2').val();
          var klaim = $('#jsetuju_2').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Disetujui tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jsetuju2').val(0);
              $('#jsetuju_2').val(0);
            }
          }
        });

        $('#jklaim3').change(function() {
          var sisa = $('#sisa3').val();
          var klaim = $('#jklaim_3').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Klaim tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jklaim3').val(0);
              $('#jklaim_3').val(0);
            }
          }
        });
        $('#jsetuju3').change(function() {
          var sisa = $('#sisa3').val();
          var klaim = $('#jsetuju_3').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Disetujui tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jsetuju3').val(0);
              $('#jsetuju_3').val(0);
            }
          }
        });

        $('#jklaim4').change(function() {
          var sisa = $('#sisa4').val();
          var klaim = $('#jklaim_4').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Klaim tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jklaim_4').val(0);
              $('#jklaim4').val(0);
            }
          }
        });
        $('#jsetuju4').change(function() {
          var sisa = $('#sisa4').val();
          var klaim = $('#jsetuju_4').val();
          var hasil = parseInt(sisa) - parseInt(klaim);
          if(hasil<0){
            var r = confirm('Jumlah Disetujui tidak mencukupi untuk proses klaim. Benefit akan diambil dari Benefit Keluarga');
            if(r == false)
            { 
              $('#jsetuju_4').val(0);
              $('#jsetuju4').val(0);
            }
          }
        });
        //BENEFIT MASK
        $('#jklaim').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jklaim").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jklaim_1").val(cloned);
        });
        $('#jsetuju').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jsetuju").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jsetuju_1").val(cloned);
        });
        $('#jklaim2').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jklaim2").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jklaim_2").val(cloned);
        });
        $('#jsetuju2').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jsetuju2").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jsetuju_2").val(cloned);
        });
        $('#jklaim3').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jklaim3").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jklaim_3").val(cloned);
        });
        $('#jsetuju3').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jsetuju3").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jsetuju_3").val(cloned);
        });
        $('#jklaim4').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jklaim4").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jklaim_4").val(cloned);
        });
        $('#jsetuju4').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
        $("#jsetuju4").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#jsetuju_4").val(cloned);
        });

        
        /*$("#benefit").click(function() {
          $('#benefit').maskMoney({prefix: 'Rp. ', 
                            thousands: '.', 
                            decimal: ',',
                            precision: 0
        });
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#benefit_1").val(cloned);
        });*/

        $("#npwp").keyup(function() {
          var clone = $(this).val();
          var cloned = clone.replace(/[A-Za-z$. ,-]/g, "")
          $("#npwp2").val(cloned);
        });
        
    });
</script>