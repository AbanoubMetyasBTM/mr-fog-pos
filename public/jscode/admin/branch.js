    $("body").on("click",".show_wallets_url",function(){
        var data=$(this).data("alldata");

        var v  = Object.entries(data);
        console.log(typeof (data));
        console.log(v);


        var html_tags = "<br><div class='row'>";
        $.each(data,function(index,value){
            html_tags+='<div class="col-md-4 mb-2">';
                    html_tags+=`<a class="btn btn-primary mg-b-2"  href="${value}" style="text-transform: capitalize; ">${index.split('_').join(' ')}</a>`;
            html_tags+='</div>';

        });

        html_tags += "</div>";

        console.log(html_tags);
        $("#showDataModal #data").html(html_tags);
        $("#showDataModal").modal("show");

        return false;
    });