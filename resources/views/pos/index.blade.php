<!DOCTYPE html>
<html>
<head>
	<title>Shopping</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<!-- Grey with black text -->
<nav class="navbar navbar-expand-sm">
    <marquee>POS - SHOPPING</marquee>
</nav>
<div class="row">
	<div class="col-md-4" style=" min-height: 400px">
		<!-- Quan ly don hang + tinh tien -->
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Product Name</th>
					<th>Amount</th>
					<th>Unit Price</th>
					<th>Total Money</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="cartList">
			</tbody>
		</table>
		<p style="font-size: 25px;">
			Total Product Cost : <label id="total" style="color: red"></label>
		</p>
		<p style="font-size: 25px;">
			Total Amount Paid By Customers : <input type="text" class="mt-2" name="customer_money" onkeyup="tinhtien()">
		</p>
		<p style="font-size: 25px;">
			Excess Cash : <label id="refund" style="color: red"></label>
		</p>
		<p>
			<button type="button" class="btn btn-success" onclick="hoanThanh()">Success</button>
		</p>
	</div>
	<div class="col-md-8">
		<!-- Hien thi danh sach san pham -->
		<form action="" method="get" id="MyForm">
		<div class="row" style="margin-top: 10px; margin-bottom: 30px">
				<div class="col-md-4">
					<select class="form-control" name="id_category" onchange="$('#MyForm').submit()">
<option value="">--- Choose ---</option>
@foreach ($categories as $item)
@if ($item->id == $id_category)
	<option selected="true" value="{{ $item->id }}">{{ $item->category_name }}</option>
@else
	<option value="{{ $item->id }}">{{ $item->category_name }}</option>
@endif

@endforeach
					</select>
				</div>
				<div class="col-md-5">
					<input type="text" name="s" class="form-control" placeholder="Search...">
				</div>
		</div>
			</form>
		<div class="row">
@foreach ($productList as $item)
	<div class="col-md-3">
		<img src="{{ $item->thumbnail }}" style="width: 100%;">
		<p>{{ $item->title }}</p>
		<p>{{ number_format($item->price) }}</p>
		<button class="btn btn-danger" type="button" onclick="addToCart({{ $item->id }}, '{{ $item->title }}', {{ $item->price }})">Add to Cart</button>
	</div>
@endforeach
		</div>
		{!! $productList->links() !!}
	</div>
</div>
<script type="text/javascript">
	var cartList = [];
	var total = 0;
	var json = localStorage.getItem('cart')
	if(json != null && json != '') {
		cartList = JSON.parse(json)
		showCart();
	}
	function addToCart(id, title, price) {
		isFind = false;
		for (var i = 0; i < cartList.length; i++) {
			if(cartList[i].id == id) {
				isFind = true;
				cartList[i].num++;
				break;
			}
		}
		if(!isFind) {
			cartList.push({
				'title': title,
				'price': price,
				'id': id,
				'num': 1
			})
		}
		localStorage.setItem('cart', JSON.stringify(cartList))
		showCart();
	}
	function showCart() {
		total = 0;
		$('#cartList').empty()
		for (var i = 0; i < cartList.length; i++) {
			$('#cartList').append(`<tr>
				<td>${i+1}</td>
				<td>${cartList[i].title}</td>
				<td><input type="number" class="form-control" value="${cartList[i].num}" readonly="true"></td>
				<td>${cartList[i].price}</td>
				<th>${cartList[i].price * cartList[i].num}</th>
				<th><button class="btn btn-danger">Delete</button></th>
			</tr>`)
			total += cartList[i].price * cartList[i].num
		}
		$('#total').text(total)
	}
	function tinhtien() {
		$('#refund').text($('[name=customer_money]').val()-total)
	}
	function hoanThanh() {
		$.post('{{ route('post_order') }}', {
			'_token': '{{ csrf_token() }}',
			'data': JSON.stringify(cartList),
			'id_customer': 1,
			'total': total
		}, function() {
			localStorage.removeItem('cart')
			location.reload()
		})
	}
</script>
</body>
</html>