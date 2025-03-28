document.addEventListener('DOMContentLoaded', function() {
  const orderTableBody = document.getElementById('order-table-body');
  const districtInput = document.getElementById('district-input');
  const districtSuggestions = document.getElementById('district-suggestions');
  const prevPageButton = document.getElementById('prevPage');
  const pageNumbersContainer = document.getElementById('pageNumbers');
  const nextPageButton = document.getElementById('nextPage');
  
  const limit = 5;
  let currentPage = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;

  function filterOrders() {
    const params = new URLSearchParams(window.location.search);
    params.set('page', currentPage);
    params.set('limit', limit); // Thêm limit để server tính total_pages
    fetch(`filter_orders.php?${params.toString()}`)
      .then(response => {
        if (!response.ok) throw new Error('Fetch failed');
        return response.json();
      })
      .then(data => {
        orderTableBody.innerHTML = '';
        if (data.orders && data.orders.length > 0) {
          data.orders.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${order.madonhang}</td>
              <td class="hide-index-tablet">${order.tenkhachhang}</td>
              <td>${order.ngaytao}</td>
              <td class="hide-index-mobile">${order.giatien}</td>
              <td><button class="${getStatusInfo(order.trangthai).class}">${getStatusInfo(order.trangthai).text}</button></td>
              <td>${order.diachi}</td>
              <td class="detail-info">
                <a href="orderDetail2.php?code_Product=${order.madonhang}"><i class="fa-solid fa-circle-info"></i> | </a>
                <a class="update-status-btn" data-order-id="${order.madonhang}" data-status="${order.trangthai}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
              </td>
            `;
            orderTableBody.appendChild(row);
          });

          document.querySelectorAll('.update-status-btn').forEach(btn => {
            btn.addEventListener('click', () => {
              const orderId = btn.getAttribute('data-order-id');
              const status = btn.getAttribute('data-status');
              showUpdateStatusPopup(orderId, status);
            });
          });
        } else {
          orderTableBody.innerHTML = `<tr><td colspan="7">Không có đơn hàng nào phù hợp</td></tr>`;
        }

        // Sử dụng data.total_pages thay vì data.totalPages
        const totalPages = data.total_pages !== undefined ? data.total_pages : 1;
        updatePagination(totalPages);
      })
      .catch(error => {
        orderTableBody.innerHTML = `<tr><td colspan="7">Đã xảy ra lỗi: ${error.message}</td></tr>`;
      });
  }

  function updatePagination(totalPages) {
    pageNumbersContainer.innerHTML = '';

    // Đảm bảo totalPages là số hợp lệ
    totalPages = totalPages > 0 ? totalPages : 1;

    for (let i = 1; i <= totalPages; i++) {
      const pageButton = document.createElement('button');
      pageButton.textContent = i;
      pageButton.classList.add('page-btn');
      if (i === currentPage) {
        pageButton.classList.add('active');
      }
      pageButton.addEventListener('click', () => {
        currentPage = i;
        filterOrders();
      });
      pageNumbersContainer.appendChild(pageButton);
    }

    const currentPageDisplay = document.createElement('span');

    currentPageDisplay.classList.add('current-page-display');
    pageNumbersContainer.appendChild(currentPageDisplay);

    prevPageButton.disabled = currentPage === 1;
    nextPageButton.disabled = currentPage === totalPages;

    // Cập nhật sự kiện nút phân trang
    prevPageButton.onclick = () => {
      if (currentPage > 1) {
        currentPage--;
        filterOrders();
      }
    };

    nextPageButton.onclick = () => {
      if (currentPage < totalPages) {
        currentPage++;
        filterOrders();
      }
    };
  }

  function handleDistrictInput() {
    districtInput.addEventListener('input', function() {
      const query = this.value.trim();
      if (query.length >= 1) {
        fetch(`get_districts.php?query=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            districtSuggestions.innerHTML = '';
            districtSuggestions.style.display = 'block';
            data.forEach(district => {
              const li = document.createElement('li');
              li.textContent = district;
              li.addEventListener('click', () => {
                districtInput.value = district;
                districtSuggestions.style.display = 'none';
                currentPage = 1; // Reset về trang 1 khi lọc mới
                filterOrders();
              });
              districtSuggestions.appendChild(li);
            });
          });
      } else {
        districtSuggestions.style.display = 'none';
      }
    });
  }

  function getStatusInfo(status) {
    switch (status) {
      case 'processing': return { text: 'Đã xác nhận', class: 'status pending4' };
      case 'pending': return { text: 'Đang xử lý', class: 'status pending' };
      case 'completed': return { text: 'Đã giao', class: 'status pending2' };
      case 'canceled': return { text: 'Đã hủy', class: 'status pending3' };
      default: return { text: 'Đã xác nhận', class: 'status pending4' };
    }
  }

  function showUpdateStatusPopup(orderId, currentStatus) {
    const overlay = document.getElementById('updateStatusOverlay');
    const statusOptions = document.getElementById('statusOptions');
    statusOptions.innerHTML = '';

    const statusFlow = ['processing', 'pending', 'completed', 'canceled'];
    const statusLabels = {
      'processing': 'Đã xác nhận',
      'pending': 'Đang xử lý',
      'completed': 'Đã giao',
      'canceled': 'Đã hủy'
    };

    const currentIndex = statusFlow.indexOf(currentStatus);
    
    statusFlow.forEach((status, index) => {
      const button = document.createElement('button');
      button.textContent = statusLabels[status];
      button.disabled = index <= currentIndex;
      if (!button.disabled) {
        button.onclick = () => updateOrderStatus(orderId, status);
      }
      statusOptions.appendChild(button);
    });

    overlay.style.display = 'flex';
  }

  function closeUpdateStatusPopup() {
    document.getElementById('updateStatusOverlay').style.display = 'none';
  }

  function updateOrderStatus(orderId, newStatus) {
    if (confirm(`Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng ${orderId} thành "${getStatusInfo(newStatus).text}"?`)) {
      fetch('updateStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orderId: orderId, status: newStatus })
      })
      .then(response => {
        if (!response.ok) throw new Error('Update failed');
        return response.json();
      })
      .then(data => {
        if (data.success) {
          alert('Cập nhật trạng thái thành công!');
          closeUpdateStatusPopup();
          filterOrders();
        } else {
          alert('Cập nhật thất bại: ' + data.error);
        }
      })
      .catch(error => {
        alert('Đã xảy ra lỗi: ' + error.message);
      });
    }
  }

  window.showUpdateStatusPopup = showUpdateStatusPopup;
  window.updateOrderStatus = updateOrderStatus;
  window.closeUpdateStatusPopup = closeUpdateStatusPopup;
  handleDistrictInput();
  filterOrders(); // Gọi lần đầu để tải dữ liệu
});