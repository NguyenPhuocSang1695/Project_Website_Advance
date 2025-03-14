sửa lại phần này ở các file css khác

.product-list {
display: none;
position: absolute;
top: 100%;
background-color: #fff;
width: 100%;
border: 1px solid #ddd;
box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
z-index: 1000;
max-height: 300px;
overflow-y: auto;

}

.product-list .product {
display: flex;
padding: 10px;
border-bottom: 1px solid #dddf;
cursor: pointer;
transition: background-color 0.3s;
}

.product-list .product:hover {
background-color: #f4f4f4;
}

.product-list .product img {
width: 21%;
height: auto;
object-fit: cover;
margin-right: 10px;
}
