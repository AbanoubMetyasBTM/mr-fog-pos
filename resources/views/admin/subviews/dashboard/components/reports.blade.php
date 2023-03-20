<?php if(count($reportLinksChunks)): ?>
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">Reports</h6>
    </div>

    <?php foreach($reportLinksChunks as $key=>$reportLinks): ?>
        <div class="card card-dash-one my-2">
            <div class="row no-gutters">
                <?php foreach($reportLinks as $reportTitle=>$links): ?>
                    <div class="col-lg-3">
                        <i class="icon ion-ios-bookmarks-outline tx-teal"></i>
                        <div class="dash-content">
                            <label class="tx-success">
                                {{$reportTitle}}
                            </label>
                            <br>
                            <?php foreach($links as $linkTitle=>$link): ?>
                                <a href="{{$link}}">
                                    {{$linkTitle}}
                                </a> -
                            <?php endforeach; ?>
                        </div><!-- dash-content -->
                    </div><!-- col-3 -->
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
