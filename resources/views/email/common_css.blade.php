<style>

    .table-form thead th {
        border: none;
        background-color: rgba(128, 137, 150, 0.1);
    }

    .table td, .table th {
        padding: .75rem;
        font-size: 15px;
    }

    .table-form th {
        color: #0d233e;
    }

    .booking_body_inner {
        width: 70%;
        margin: 0 auto;
        padding: 0;
        -premailer-width: 70%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        background-color: #FFFFFF;
    }

    .header_title {
        padding: 10px;
        background: aliceblue;
    }

    .d-flex {
        display: -ms-flexbox !important;
        display: flex !important;
    }

    ul {
        padding: 0;
        margin: 0;
        list-style-type: none;
    }

    .pt-2, .py-2 {
        padding-top: .5rem !important;
    }

    .row {
        display: flex;
    }

    .col-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }


    .step-bar-list .step-bar {
        position: relative;
        z-index: 1;
    }

    .flex-grow-1 {
        -ms-flex-positive: 1 !important;
        flex-grow: 1 !important;
    }

    .step-bar-list .step-bar.step-bar-active::before, .step-bar-list .step-bar.step-bar-active::after {
        background-color: #40CC6F;
    }

    .step-bar-list .step-bar::after {
        left: auto !important;
        right: 0;
    }

    .step-bar-list .step-bar::before, .step-bar-list .step-bar::after {
        display: block;
        position: absolute;
        background-color: #F5F7FC;
        height: 4px;
        content: '';
        width: 50%;
        left: 0;
        top: 25px;
        z-index: -1;
    }

    .step-bar-list .step-bar.step-bar-active .icon-element {
        position: relative;
        background-color: #40CC6F;
        border-color: #40CC6F;
    }

    .step-bar-list .step-bar .icon-element {
        text-indent: 0;
        background-color: #fff;
        color: #0d233e;
        -webkit-box-shadow: 0 0 40px rgb(82 85 90 / 10%);
        -moz-box-shadow: 0 0 40px rgba(82, 85, 90, 0.1);
        box-shadow: 0 0 40px rgb(82 85 90 / 10%);
        border: 2px solid rgba(128, 137, 150, 0.2);
    }

    .icon-element {
        display: block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        background-color: #287dfa;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        position: relative;
        font-size: 25px;
        color: #fff !important;
    }

    .list-items li {
        margin-bottom: 6px;
        color: #5d646d;
    }

    .list-items-2 li span {
        display: inline-block;
        width: 150px;
        color: #0d233e;
        font-weight: 500;
    }

</style>
