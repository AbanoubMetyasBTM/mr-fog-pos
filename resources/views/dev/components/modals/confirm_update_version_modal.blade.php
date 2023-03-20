<!-- MODAL EFFECTS -->
<div id="confirmUpdateVersionModal" class="modal fade">
    <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-body pd-25">
                <h5 class="lh-3 mg-b-20 tx-inverse">هل أنت متأكد ؟</h5>

                <div class="content_body">

                    <div style="text-align: center;">
                        <img src="{{get_image_or_default("public/images/warning.png")}}-100,100"
                             style="width: 100px;height: 100px;text-align: center;">
                    </div>

                    <p class="mg-b-5">
                        <b>برجاء أخذ نسخه إحتياطية للملفات وقاعدة البيانات قبل إتمام التحديث</b>
                        <br><br>
                        <b>سيتم إيقاف التطبيق مؤقتا أثناء إجراء التحديث</b>
                    </p>

                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary confirm_update">نعم</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">لا</button>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->