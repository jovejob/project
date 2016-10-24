<!DOCTYPE html>
<html>
    <head>
        <meta name="_token" content="{{ csrf_token() }}"/>
        <title>Laravel</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <!--jQuery-->
        <script type="text/javascript" src="{{ asset('js/jquery-3.1.1.js') }}"></script>

        {{--<style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: center;
                display: inline-block;
            }
            .title {
                font-size: 96px;
            }
        </style>--}}
    </head>
    <body>
    <div>
        <!-- Generate a form for adding article -->
        {!! Form::open([
            'route' => 'add.product',
         ]) !!}
        <div class="form-group">
            <label for="name">Product Name</label>
            <input id="name" type="text" name="title">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity in stock</label>
            <input id="quantity" type="text" name="quantity">
        </div>
        <div class="form-group">
            <label for="price">Price per item</label>
            <input id="price" type="text" name="price">
        </div>
        <div class="error-form">
            
        </div>
        <div class="form-group">
            <input id="submit" type="submit" value="Send">
        </div>
        {!! Form::close() !!}
    </div>
    <div class="result">
        <table id="table" width="100%">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in stock</th>
                <th>Price per item</th>
                <th>Date</th>
                <th>Total value number</th>
            </tr>
            </thead>
            <tbody id="table-body">

            @if(isset($products))
                @foreach($products as $key=> $value)
                    <?php
                    $value = json_decode($value);
                    $total = $value->price * $value->quantity;
                    ?>
                    <tr>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->quantity }}</td>
                        <td>{{ $value->price }}</td>
                        <td>{{ $value->datetime }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    </body>
<script>
    // contact form
    $(document).on("click","#submit",function(e) {
        e.preventDefault();
        var name = $(this).parent().parent().find('#name').val();
        var quantity = $(this).parent().parent().find('#quantity').val();
        var price = $(this).parent().parent().find('#price').val();
        $.post("add-product", {
            '_token': $('meta[name=_token]').attr('content'),
            name: name,
            quantity: quantity,
            price: price
        }).done(function(product) {
            var total = parseInt(product.product.price) * parseInt(product.product.quantity);
            $('#table-body').append(
                    '<tr>'+
                            '<td>'+product.product.name+'</td>'+
                            '<td>'+product.product.quantity+'</td>'+
                            '<td>'+product.product.price+'</td>'+
                            '<td>'+product.product.datetime+'</td>'+
                            '<td>'+total+'</td>'+
                    '</tr>'
            );
            $("#name").val('');
            $("#quantity").val('');
            $("#price").val('');
            $('.error-form').html('');
          })
          .fail(function($get) {

            $('.error-form').html('')
             $.each($get.responseJSON,function(index,value){
                $('.error-form').append(value+'<br />');
             });
          });
    });
</script>
</html>