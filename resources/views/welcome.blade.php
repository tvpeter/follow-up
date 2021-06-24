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
            <button class="btn btn-primary btn-block my-4" id="createProduct" type="submit">Submit</button>
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

        {{-- update modal --}}

            <div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="editProductLabell" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabell">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="updateForm">
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="name" id="edit-name" class="form-control mb-4" placeholder="Product name" >

                        <input type="number"  name="quantity" id="edit-qty" class="form-control mb-4" placeholder="Quantity" >

                        <input type="number" name="price" id="edit-price" class="form-control mb-4" placeholder="Price">
                        
                    </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateProduct">Save changes</button>
                    </div>
                </div>
                </div>
            </div>
            
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
                                +"<td>"+ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(totalValue) +"</td>" 
                                +"<td><a class='btn btn-primary' data-id='"+row.id+"' data-price='"+row.price+"' data-quantity='"+row.quantity+"' data-name='"+row.name+"' data-bs-toggle='modal' id='displayModal' data-bs-target='#editProduct'>Edit</a>" 
                                +"</td>";
                                tableRow+="</tr>";
                        });
                         $("#displayData").append(tableRow);
                         $('#sumDisplay').html(new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(sum));

                    }
                });

            });

            $(document).ready(function(){
            $("#createProduct").click(function(event){
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

             //display edit form with data
             $(document).on("click", "#displayModal", function () {
                 var productId = $(this).data('id');
                 var productPrice = $(this).data('price');
                 var productQty = $(this).data('quantity');
                 var productName = $(this).data('name');
        

                $(".modal-body #id").val( productId );
                $(".modal-body #edit-price").val( productPrice );
                $(".modal-body #edit-name").val( productName );
                $(".modal-body #edit-qty").val( productQty );
            });



            $(document).ready(function(){
            $("#updateProduct").click(function(event){
                    event.preventDefault();

                    let editedId = $('#id').val();
                    let editedName = $("#edit-name").val();
                    let editedQty = $("#edit-qty").val();
                    let editedPrice = $("#edit-price").val();
                    let _putToken   = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: "/products",
                        type:"PUT",
                        data:{
                            name:editedName,
                            id:editedId,
                            quantity:editedQty,
                            price:editedPrice,
                            _token:_putToken
                        },
                        success:function(response) {
                            $('#editProduct').modal('hide');
                            
                            swal(response.message, "success", "success");
                            $("#updateForm")[0].reset();
                        },
                        error:function (response) {
                            $('#editProduct').modal('hide');
                            swal(response.responseJSON.message, "please retry", "error");
                        }
                    });

                });
             });

        

        </script>
    </body>
</html>
