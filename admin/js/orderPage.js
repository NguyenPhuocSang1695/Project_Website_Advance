document.addEventListener('DOMContentLoaded', function() {
  const orderTableBody = document.getElementById('order-table-body');
  const districtInput = document.getElementById('district-input');
  const districtSuggestions = document.getElementById('district-suggestions');
  const cityInput = document.getElementById('city-input');
  const citySuggestions = document.getElementById('city-suggestions');
  const prevPageButton = document.getElementById('prevPage');
  const pageNumbersContainer = document.getElementById('pageNumbers');
  const nextPageButton = document.getElementById('nextPage');
  
  const limit = 5;
  let currentPage = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;
  
  window.applyFilters = function() {
    currentPage = 1;
    filterOrders();
  };
  
  function filterOrders() {
    const dateFrom = document.getElementById('date-from')?.value || '';
    const dateTo = document.getElementById('date-to')?.value || '';
    const orderStatus = document.getElementById('order-status')?.value || 'all';
    const district = document.getElementById('district-input')?.value.trim() || '';
    const province = document.getElementById('city-input')?.value.trim() || '';

    const params = new URLSearchParams({
        page: currentPage,
        limit: limit
    });

    if (dateFrom) params.set('date_from', dateFrom);
    if (dateTo) params.set('date_to', dateTo);
    if (orderStatus && orderStatus !== 'all') params.set('order_status', orderStatus);
    if (district) params.set('district', district);
    if (province) params.set('city', province);

    window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);

    fetch(`filter_orders.php?${params.toString()}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.text();
      })
      .then(text => {
        console.log('Raw response from filter_orders:', text);
        return JSON.parse(text);
      })
      .then(data => {
        if (!orderTableBody) {
          console.error('Element order-table-body not found');
          return;
        }
        
        orderTableBody.innerHTML = '';
        if (data.success && data.orders && data.orders.length > 0) {
          data.orders.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${order.madonhang}</td>
              <td class="hide-index-tablet">${order.fullname}</td>
              <td>${formatDate(order.ngaytao)}</td>
              <td class="hide-index-mobile">${order.giatien}</td>
              <td><button class="${getStatusInfo(order.trangthai).class}">${getStatusInfo(order.trangthai).text}</button></td>
              <td>${formatAddress(order.address)}</td>
              <td class="detail-info">
                <a href="orderDetail2.php?code_Product=${order.madonhang}" class="action-btn view-btn">
                  <i class="fa-solid fa-circle-info"></i>
                </a>
                <a class="update-status-btn action-btn edit-btn" data-order-id="${order.madonhang}" data-status="${order.trangthai}">
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
          orderTableBody.innerHTML = `<tr><td colspan="7" class="no-data">Không có đơn hàng nào phù hợp</td></tr>`;
        }
        const totalPages = data.total_pages !== undefined ? data.total_pages : 1;
        updatePagination(totalPages);
      })
      .catch(error => {
        console.error('Error fetching orders:', error);
        if (orderTableBody) {
          orderTableBody.innerHTML = `<tr><td colspan="7" class="error-message">Đã xảy ra lỗi: ${error.message}</td></tr>`;
        }
      });
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  function formatAddress(address, district, province) {
    let fullAddress = address || '';
    if (district) fullAddress += district ? `, ${district}` : '';
    if (province) fullAddress += city ? `, ${province}` : '';
    return fullAddress;
  }

  function updatePagination(totalPages) {
    if (!pageNumbersContainer) {
      console.error('Element pageNumbers not found');
      return;
    }
    
    pageNumbersContainer.innerHTML = '';
    totalPages = totalPages > 0 ? totalPages : 1;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    if (startPage > 1) {
      const firstPageBtn = document.createElement('button');
      firstPageBtn.textContent = '1';
      firstPageBtn.classList.add('page-btn');
      firstPageBtn.addEventListener('click', () => {
        currentPage = 1;
        filterOrders();
      });
      pageNumbersContainer.appendChild(firstPageBtn);
      
      if (startPage > 2) {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.classList.add('ellipsis');
        pageNumbersContainer.appendChild(ellipsis);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
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

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.classList.add('ellipsis');
        pageNumbersContainer.appendChild(ellipsis);
      }
      
      const lastPageBtn = document.createElement('button');
      lastPageBtn.textContent = totalPages;
      lastPageBtn.classList.add('page-btn');
      lastPageBtn.addEventListener('click', () => {
        currentPage = totalPages;
        filterOrders();
      });
      pageNumbersContainer.appendChild(lastPageBtn);
    }

    if (prevPageButton) {
      prevPageButton.disabled = currentPage === 1;
      prevPageButton.onclick = () => {
        if (currentPage > 1) {
          currentPage--;
          filterOrders();
        }
      };
    }

    if (nextPageButton) {
      nextPageButton.disabled = currentPage === totalPages;
      nextPageButton.onclick = () => {
        if (currentPage < totalPages) {
          currentPage++;
          filterOrders();
        }
      };
    }
  }

  function handleDistrictInput() {
    if (!districtInput || !districtSuggestions) return;
    
    districtInput.addEventListener('input', function() {
      const query = this.value.trim();
      if (query.length >= 1) {
        fetch(`get_Address.php?type=district&query=${encodeURIComponent(query)}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
          })
          .then(text => {
            console.log('Raw response from get_Address (district):', text);
            return JSON.parse(text);
          })
          .then(data => {
            districtSuggestions.innerHTML = '';
            districtSuggestions.style.display = 'block';
            if (data.success) {
              data.data.forEach(district => {
                const li = document.createElement('li');
                li.textContent = district;
                li.addEventListener('click', () => {
                  districtInput.value = district;
                  districtSuggestions.style.display = 'none';
                });
                districtSuggestions.appendChild(li);
              });
            }
          })
          .catch(error => {
            console.error('Error fetching district suggestions:', error);
          });
      } else {
        districtSuggestions.style.display = 'none';
      }
    });

    document.addEventListener('click', function(e) {
      if (e.target !== districtInput && e.target !== districtSuggestions) {
        districtSuggestions.style.display = 'none';
      }
    });
  }

  function handleProvinceInput() {
    if (!cityInput || !citySuggestions) return;
    
    cityInput.addEventListener('input', function() {
      const query = this.value.trim();
      if (query.length >= 1) {
        fetch(`get_Address.php?type=city&query=${encodeURIComponent(query)}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
          })
          .then(text => {
            console.log('Raw response from get_Address (province):', text);
            return JSON.parse(text);
          })
          .then(data => {
            citySuggestions.innerHTML = '';
            citySuggestions.style.display = 'block';
            if (data.success) {
              data.data.forEach(province => {
                const li = document.createElement('li');
                li.textContent = province;
                li.addEventListener('click', () => {
                  cityInput.value = province;
                  citySuggestions.style.display = 'none';
                  if (districtInput) {
                    districtInput.value = '';
                  }
                });
                citySuggestions.appendChild(li);
              });
            }
          })
          .catch(error => {
            console.error('Error fetching city suggestions:', error);
          });
      } else {
        citySuggestions.style.display = 'none';
      }
    });

    document.addEventListener('click', function(e) {
      if (e.target !== cityInput && e.target !== citySuggestions) {
        citySuggestions.style.display = 'none';
      }
    });
  }
 
  function getStatusInfo(status) {
    switch (status) {
      case 'processing': return { text: 'Đã xác nhận', class: 'status processing', tooltip: 'Đơn hàng đã được xác nhận' };
      case 'pending': return { text: 'Đang xử lý', class: 'status pending', tooltip: 'Đơn hàng đang chờ xử lý' };
      case 'shipped': return { text: 'Đang giao', class: 'status shipped', tooltip: 'Đơn hàng đang được giao' };
      case 'completed': return { text: 'Đã giao', class: 'status completed', tooltip: 'Đơn hàng đã được giao thành công' };
      case 'canceled': return { text: 'Đã hủy', class: 'status canceled', tooltip: 'Đơn hàng đã bị hủy' };
      default: return { text: 'Không xác định', class: 'status unknown', tooltip: 'Trạng thái không xác định' };
    }
  }

  function showUpdateStatusPopup(orderId, currentStatus) {
    const overlay = document.getElementById('updateStatusOverlay');
    if (!overlay) {
      console.error('Element updateStatusOverlay not found');
      return;
    }
    
    const statusOptions = document.getElementById('statusOptions');
    if (!statusOptions) {
      console.error('Element statusOptions not found');
      return;
    }
    
    const statusFlow = {
        'pending': ['processing', 'canceled'], // Đang xử lý → Đã xác nhận hoặc Đã hủy
        'processing': ['shipped', 'canceled'], // Đã xác nhận → Đang giao hoặc Đã hủy
        'shipped': ['completed', 'canceled'], // Đang giao → Đã giao hoặc Đã hủy
        'completed': [], // Đã giao → Kết thúc
        'canceled': [] // Đã hủy → Kết thúc
    };

    const statusLabels = {
        'pending': 'Đang xử lý',
        'processing': 'Đã xác nhận',
        'shipped': 'Đang giao',
        'completed': 'Đã giao',
        'canceled': 'Đã hủy'
    };

    statusOptions.innerHTML = '';

    statusFlow[currentStatus].forEach((status) => {
        const button = document.createElement('button');
        button.textContent = statusLabels[status];
        button.disabled = false; // Enable only allowed transitions
        button.addEventListener('click', () => {
            updateOrderStatus(orderId, status);
            overlay.style.display = 'none';
        });
        statusOptions.appendChild(button);
    });

    // Disable the current status button
    const currentStatusButton = document.createElement('button');
    currentStatusButton.textContent = statusLabels[currentStatus];
    currentStatusButton.disabled = true;
    currentStatusButton.classList.add('current-status');
    statusOptions.appendChild(currentStatusButton);

    const orderIdDisplay = document.getElementById('orderIdDisplay');
    if (orderIdDisplay) {
        orderIdDisplay.textContent = orderId;
    }

    overlay.style.display = 'flex';

    const closeButton = document.getElementById('closeUpdateStatus');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            overlay.style.display = 'none';
        });
    }

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.style.display = 'none';
        }
    });
  }

  function updateOrderStatus(orderId, newStatus) {
    fetch('updateStatus.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        orderId: orderId,
        status: newStatus
      })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.text();
    })
    .then(text => {
      console.log('Raw response from updateStatus:', text);
      return JSON.parse(text);
    })
    .then(data => {
      if (data.success) {
        showNotification('Cập nhật trạng thái thành công!', 'success');
        filterOrders();
      } else {
        showNotification('Lỗi khi cập nhật trạng thái: ' + (data.error || 'Unknown error'), 'error');
      }
    })
    .catch(error => {
      showNotification('Đã xảy ra lỗi: ' + error.message, 'error');
    });
  }

  function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
      notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 3000);
  }

  function initPage() {
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        currentPage = 1;
        filterOrders();
      });
    }
    
    const resetButton = document.getElementById('reset-filters');
    if (resetButton) {
      resetButton.addEventListener('click', function() {
        const dateFrom = document.getElementById('date-from');
        const dateTo = document.getElementById('date-to');
        const orderStatus = document.getElementById('order-status');
        
        if (dateFrom) dateFrom.value = '';
        if (dateTo) dateTo.value = '';
        if (orderStatus) orderStatus.value = 'all';
        if (districtInput) districtInput.value = '';
        if (cityInput) cityInput.value = '';
        
        currentPage = 1;
        filterOrders();
      });
    }
    
    handleDistrictInput();
    handleProvinceInput();
    
    filterOrders();
  }

  initPage();
});

document.addEventListener("DOMContentLoaded", () => {
  // Ensure tooltips are displayed on hover
  const statusElements = document.querySelectorAll(".status-tooltip");
  statusElements.forEach((element) => {
    element.addEventListener("mouseenter", () => {
      const tooltip = element.querySelector(".tooltip-popup");
      if (tooltip) tooltip.style.display = "block";
    });

    element.addEventListener("mouseleave", () => {
      const tooltip = element.querySelector(".tooltip-popup");
      if (tooltip) tooltip.style.display = "none";
    });
  });

  const iconElements = document.querySelectorAll(".icon-tooltip");
  iconElements.forEach((element) => {
    element.addEventListener("mouseenter", () => {
      const tooltip = element.querySelector(".tooltip-popup");
      if (tooltip) tooltip.style.display = "block";
    });

    element.addEventListener("mouseleave", () => {
      const tooltip = element.querySelector(".tooltip-popup");
      if (tooltip) tooltip.style.display = "none";
    });
  });
});
function closeUpdateStatusPopup() {
  document.getElementById('updateStatusOverlay').style.display = 'none';
}
  function resetFilters() {
  document.getElementById('date-from').value = '';
  document.getElementById('date-to').value = '';
  document.getElementById('order-status').value = 'all';

  document.getElementById('city-input').value = '';
  document.getElementById('district-input').value = '';
document.getElementById('city-suggestions').style.display = 'none';
  document.getElementById('district-suggestions').style.display = 'none';
  location.reload();
}
