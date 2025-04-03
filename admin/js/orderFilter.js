document.addEventListener('DOMContentLoaded', function() {
    const searchBar = document.getElementById('search-bar-order');
    const suggestionsList = document.getElementById('suggestions');
    const searchButton = document.getElementById('search-button');
    const orderTableBody = document.getElementById('order-table-body');

    // Tải danh sách đơn hàng ban đầu
    filterOrders('');

    // Xử lý khi người dùng nhập vào ô tìm kiếm (Autocomplete)
    searchBar.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length >= 2) {
            fetch(`search_address.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    suggestionsList.style.display = 'block';
                    if (data.length > 0) {
                        data.forEach(address => {
                            const li = document.createElement('li');
                            li.textContent = address;
                            li.addEventListener('click', () => {
                                searchBar.value = address;
                                suggestionsList.style.display = 'none';
                                filterOrders(address);
                            });
                            suggestionsList.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.textContent = 'Không tìm thấy địa chỉ';
                        suggestionsList.appendChild(li);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            suggestionsList.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchBar.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.style.display = 'none';
        }
    });

    // Xử lý khi nhấn nút tìm kiếm
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        const query = searchBar.value.trim();
        if (query) {
            filterOrders(query);
        }
    });

    // Hàm lọc đơn hàng và hiển thị
    function filterOrders(address) {
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('end-date').value;
        const orderStatus = document.getElementById('order-status').value;
        const district = document.getElementById('district').value;

        const params = new URLSearchParams({
            address: address,
            date_from: dateFrom,
            date_to: dateTo,
            order_status: orderStatus,
            district: district
        });

        fetch(`filter_orders.php?${params.toString()}`)
            .then(response => response.json())
            .then(orders => {
                orderTableBody.innerHTML = '';
                if (orders.length > 0) {
                    orders.forEach(order => {
                        const statusInfo = getStatusInfo(order.trangthai);
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${order.madonhang}</td>
                            <td class="hide-index-tablet">${order.tenkhachhang}</td>
                            <td>${order.ngaytao}</td>
                            <td class="hide-index-mobile">${order.giatien}</td>
                            <td>
                                <button class="${statusInfo.class}">${statusInfo.text}</button>
                            </td>
                            <td>${order.diachi}</td>
                            <td class="detail-info">
                                <a href="orderDetail2.php?code_Product=${order.madonhang}">
                                    <i class="fa-solid fa-circle-info"></i> | 
                                </a>
                                <a><i class="fa-solid fa-pen-to-square"></i></a>
                            </td>
                        `;
                        orderTableBody.appendChild(row);
                    });
                } else {
                    orderTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center;">Không có đơn hàng nào phù hợp</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                orderTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center;">Đã xảy ra lỗi khi tải dữ liệu</td></tr>`;
            });
    }

    // Hàm ánh xạ trạng thái và class CSS
    function getStatusInfo(status) {
        switch (status) {
            case 'pending':
                return { text: 'Đang xử lý', class: 'status pending' };
            case 'processing':
                return { text: 'Đã xác nhận', class: 'status pending4' };
            // case 'shipped':
            //     return { text: 'Đang giao', class: 'status pending2' };
            case 'completed':
                return { text: 'Đã giao', class: 'status pending2' };
            case 'canceled':
                return { text: 'Đã hủy', class: 'status pending3' };
            default:
                return { text: 'Đã giao', class: 'status pending2' };
        }
    }
});