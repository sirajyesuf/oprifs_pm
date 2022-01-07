<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>
@auth('web')
    <?php $url = route('invoices.show',[$currentWorkspace->slug,$invoice->id]); ?>
@elseauth
    <?php $url = route('client.invoices.show',[$currentWorkspace->slug,$invoice->id]); ?>
@endauth
<script>
    'use strict';

    function closeScript() {
        setTimeout(function () {
            window.location.href = '{{ $url }}';
        }, 1000);
    }

    $(window).on('load', function () {
        var element = document.getElementById('boxes');
        var opt = {
            filename: '{{App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };
        html2pdf().set(opt).from(element).save().then(closeScript);
    });
</script>
