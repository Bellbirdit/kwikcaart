<div class="tab-pane fade" id="feedback" role="tabpanel" aria-labelledby="feedback-tab">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Order Feedback</h3>
        </div>
        <div class="card-body">
        <form id="brand_form">

                <div class="form-group">
                    
                    <input type="text" class="form-control" name="order_number" placeholder="Enter order number">
                </div>
                <div class="form-group">
                    
                    <input type="text" class="form-control" name="heading" placeholder="Enter heading">
                </div>
                <div class="form-group">
                    
                    <textarea name="description" rows="5" placeholder="Enter description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary  " id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save </button>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(e) {      
        $("#brand_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/feedback/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#brand_form")[0].reset();
                        // location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
    });
</script>