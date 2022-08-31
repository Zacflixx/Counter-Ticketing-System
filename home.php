<br>
<h5>Please select an option below</h5>
 
<hr>
<div class="col-12">
    <div class="col-md-12">
       <br><br><br>

              <div style="display:flex; flex:100%;">
                <div style="flex:50%;">
                <center>
                     <a href="./queue_registration.php" class="ms-1" target="blank"><button class="btn btn-primary" style="font-size: 1.5rem !important;height: 4rem;" type="submit">Customer Center</button></a>
                </center>
                </div>
                <div style="flex:50%;">
                <center>
                    <a href="./cashier" class="ms-1" target="blank"><button class="btn btn-primary" style="font-size: 1.5rem !important;height: 4rem;" type="submit">Counter Manager</button></a>
                </center>
                </div>
              </div>
    
    </div>
</div>

<script>
    $(function(){
        $('#upload-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('updating video...')
            $.ajax({
                url:'./Actions.php?a=update_video',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Update')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        location.reload()
                        if("<?php echo isset($department_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>