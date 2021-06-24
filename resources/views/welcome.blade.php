<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Follow up test</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital@1&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <style>
            body{
                font-family: 'Poppins', sans-serif;
                            }
        </style>
    </head>
    <body>

        <div class="container w-75">
            <div class="row">

                @if ($errors->any())
                <script>
                    swal("Error creating your product", "{{$errors->first()}}", "error");
                </script>
                @endif


        <form class="text-center border border-light p-5" id="submitform">    
            <p class="h4 mb-4">Add Product</p>
            <input type="text" name="name" class="form-control mb-4" placeholder="Product name" required>

            <input type="number"  name="quantity"  class="form-control mb-4" placeholder="Quantity" required>

            <input type="number" name="price" class="form-control mb-4" placeholder="Price" required>
            <button class="btn btn-primary btn-block my-4" type="submit">Submit</button>
        </form>

         </div>

         <div class="row">
            <table class="table" id="myTable">
                <thead>
                  <tr>
                      <th>SN</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity in Stock</th>
                    <th scope="col">Price per Item</th>
                    <th scope="col">DateTime Submitted</th>
                    <th>Total Value</th>
                    <th>Edit</th>
                  </tr>
                </thead>
                <tbody id="displayData">
                   

                    <tfoot>
                        <td colspan="4">SUM TOTAL</td>
                        
                        <td colspan="2" class="text-end fw-bold" id="sumDisplay">&#36;</td>
                    </tfoot>
                  
                </tbody>
              </table>
         </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
        <script>

            $(document).ready(function(){
                $.ajax({
                    url: "/products",
                    type: "get",
                    data:{ 
                        _token:'{{ csrf_token() }}'
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(response){
                        var tableRow = '';
                        var i=1;
                        var products = response.products;
                        var sum = 0;
                        $.each(products, function(index, row){
                                var totalValue = row.quantity * row.price;
                                sum += totalValue;
                              tableRow +="<tr>"
                                tableRow+="<td>"+ i++ +"</td><td>"+row.name+"</td><td>"+row.quantity+"</td><td>"+ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(row.price) +"</td>"
                                +"<td>"+ new Date(row.created_at).toLocaleString()  +"</td>"
                                +"<td>"+ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(totalValue) +"</td><td><a class='btn btn-primary' href='"+''+"'>Edit</a>" 
                                +"</td>";
                                tableRow+="</tr>";
                        });
                         $("#displayData").append(tableRow);
                         $('#sumDisplay').html(new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(sum));

                    }
                });

            });

            $(document).ready(function(){
            $(".btn").click(function(event){
                    event.preventDefault();

                    let name = $("input[name=name]").val();
                    let quantity = $("input[name=quantity]").val();
                    let price = $("input[name=price]").val();
                    let _token   = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: "/product",
                        type:"POST",
                        data:{
                            name,
                            quantity,
                            price,
                            _token
                        },
                        success:function(response) {

                            swal(response.message, "success", "success");
                            $("#submitform")[0].reset();
                        },
                        error:function (response) {
                            swal(response.responseJSON.message, "please retry", "error");
                        }
                    });

                });
             });
        </script>
    </body>
</html>
