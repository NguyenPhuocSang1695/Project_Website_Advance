document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analyze-form');
    const customerTable = document.getElementById('customer-table');
    const productTable = document.getElementById('product-table');
    const totalRevenue = document.getElementById('total-revenue');
    const bestSelling = document.getElementById('best-selling');
    const worstSelling = document.getElementById('worst-selling');
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');
    const bestSellingQuantity = document.getElementById('best-selling-quantity');
    const worstSellingQuantity = document.getElementById('worst-selling-quantity');
    const modal = document.getElementById('orderDetailModal');
    const closeBtn = document.querySelector('.order-modal-close');
  
    // Khôi phục giá trị filter từ localStorage
    function restoreFilterValues() {
      const savedStartDate = localStorage.getItem('analyze_start_date');
      const savedEndDate = localStorage.getItem('analyze_end_date');
      
      if (savedStartDate) {
        startDate.value = savedStartDate;
      } else {
        startDate.value = new Date().toISOString().slice(0, 8) + '01';
      }
      
      if (savedEndDate) {
        endDate.value = savedEndDate;
      } else {
        endDate.value = new Date().toISOString().slice(0, 10);
      }
    }

    function saveFilterValues() {
      localStorage.setItem('analyze_start_date', startDate.value);
      localStorage.setItem('analyze_end_date', endDate.value);
    }
  
    function formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    }
  
    function formatCurrency(number) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(number).replace('₫', 'VNĐ');
    }

    function formatPercentage(number) {
      return number.toFixed(2) + '%';
    }
  
    function showError(message) {
      if (customerTable) {
        customerTable.innerHTML = `<tr><td colspan="6" style="text-align: center;">${message}</td></tr>`;
      }
      if (productTable) {
        productTable.innerHTML = `<tr><td colspan="6" style="text-align: center;">${message}</td></tr>`;
      }
      if (totalRevenue) {
        totalRevenue.textContent = '0 VNĐ';
      }
      if (bestSelling) {
        bestSelling.textContent = 'Chưa có dữ liệu';
      }
      if (worstSelling) {
        worstSelling.textContent = 'Chưa có dữ liệu';
      }
      if (bestSellingQuantity) {
        bestSellingQuantity.textContent = '';
      }
      if (worstSellingQuantity) {
        worstSellingQuantity.textContent = '';
      }
    }
    function updateStatistics(data) {
  
      if (totalRevenue) {
        totalRevenue.innerHTML = `<span class="value">${formatCurrency(data.total_revenue || 0)}</span>`;
        if (data.revenue_change) {
          const changeClass = data.revenue_change > 0 ? 'positive-change' : 'negative-change';
          const changeIcon = data.revenue_change > 0 ? 'fa-arrow-up' : 'fa-arrow-down';
          totalRevenue.innerHTML += `
            <span class="change ${changeClass}">
              <i class="fa-solid ${changeIcon}"></i>
              ${Math.abs(data.revenue_change)}% so với kỳ trước
            </span>
          `;
        }
      }
       
      // mặt hàng bán chạy nhất
      if (bestSelling && data.best_selling) {
        if (typeof data.best_selling === 'string') {
          bestSelling.innerHTML = `${data.best_selling}`;
        } else {
          bestSelling.innerHTML = `
            <span class="product-name">${data.best_selling.name}</span>
          `;
          
          // Hiển thị số lượng đã bán
          if (bestSellingQuantity && data.best_selling.quantity) {
            bestSellingQuantity.innerHTML = `
              <div>Đã bán: ${data.best_selling.quantity} sản phẩm</div>
              <div>Doanh thu: ${formatCurrency(data.best_selling.revenue)}</div>
              <div>Đóng góp: ${formatPercentage(data.best_selling.contribution)} doanh thu</div>
            `;
          }
        }
      } else if (bestSelling) {
        bestSelling.innerHTML = 'Chưa có dữ liệu';
        if (bestSellingQuantity) {
          bestSellingQuantity.innerHTML = '';
        }
      }
      
      // Cập nhật mặt hàng bán ế nhất
      if (worstSelling && data.worst_selling) {
        if (typeof data.worst_selling === 'string') {
          worstSelling.innerHTML = `${data.worst_selling}`;
        } else {
          worstSelling.innerHTML = `
            <span class="product-name">${data.worst_selling.name}</span>
          `;
          
          // Hiển thị số lượng đã bán
          if (worstSellingQuantity && data.worst_selling.quantity) {
            worstSellingQuantity.innerHTML = `
              <div>Đã bán: ${data.worst_selling.quantity} sản phẩm</div>
              <div>Doanh thu: ${formatCurrency(data.worst_selling.revenue)}</div>
              <div>Đóng góp: ${formatPercentage(data.worst_selling.contribution)} doanh thu</div>
            `;
          }
        }
      } else if (worstSelling) {
        worstSelling.innerHTML = 'Chưa có dữ liệu';
        if (worstSellingQuantity) {
          worstSellingQuantity.innerHTML = '';
        }
      }
    }

    // Close modal when clicking close button or outside
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Hàm hiển thị modal với thông tin đơn hàng
    function showOrderDetail(orderId) {
        fetch(`../php/get_order_detail.php?orderId=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    
                    // Cập nhật thông tin cơ bản
                    document.getElementById('modalOrderId').textContent = order.orderId;
                    document.getElementById('modalOrderDate').textContent = formatDate(order.orderDate);
                    document.getElementById('modalPaymentMethod').textContent = order.paymentMethod;
                    document.getElementById('modalReceiverName').textContent = order.receiverName;
                    document.getElementById('modalReceiverPhone').textContent = order.receiverPhone;
                    document.getElementById('modalReceiverAddress').textContent = order.receiverAddress;
                    document.getElementById('modalTotalAmount').textContent = formatCurrency(order.totalAmount);
                    
                    // Cập nhật trạng thái đơn hàng
                    const statusBadge = document.getElementById('modalOrderStatus');
                    statusBadge.textContent = getStatusText(order.status);
                    statusBadge.className = 'status-badge status-' + order.status.toLowerCase();
                    
                    // Hiển thị danh sách sản phẩm
                    const productList = document.getElementById('modalProductList');
                    productList.innerHTML = order.products.map(product => `
                        <div class="product-item">
                            <img src="../..${product.imageUrl}" alt="${product.productName}" class="product-image">
                            <div class="product-details">
                                <div class="product-name">${product.productName}</div>
                                <div class="product-price">
                                    ${product.quantity} x ${formatCurrency(product.unitPrice)} = ${formatCurrency(product.totalPrice)}
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    // Hiển thị modal
                    modal.style.display = "block";
                } else {
                    alert('Không thể tải thông tin đơn hàng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải thông tin đơn hàng');
            });
    }
    function getStatusText(status) {
        const statusMap = {
            'execute': 'Đang xử lý',
            'ship': 'Đang giao hàng',
            'success': 'Hoàn thành',
            'fail': 'Đã hủy'
        };
        return statusMap[status] || status;
    }

    //  hiển thị đơn hàng trong bảng customer và product
    function updateCustomerTable(customers) {
        if (customerTable) {
            customerTable.innerHTML = customers.length ? 
                customers.map((customer, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${customer.customer_name}</td> 
                        <td>${customer.order_count}</td>
                        <td>${formatDate(customer.latest_order_date)}</td>
                        <td class="total-amount">${formatCurrency(customer.total_amount)}</td>
                        <td class="order-detail-link">
                            <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Xem đơn hàng
                                </button>
                                <ul class="dropdown-menu">
                                    ${customer.order_links.map(order => `
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); showOrderDetail(${order.id})">
                                                Đơn hàng #${order.id}
                                            </a>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        </td>
                    </tr> 
                `).join('') :
                '<tr><td colspan="6" style="text-align: center;">Không có dữ liệu trong khoảng thời gian này</td></tr>';
        }
    }

    // hiển thị bảng sản phẩm
    function updateProductTable(products) {
        if (productTable) {
            productTable.innerHTML = products.length ?
                products.map((product, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${product.product_name}</td>
                        <td>${product.quantity_sold}</td>
                        <td class="total-amount">${formatCurrency(product.total_amount)}</td>
                        <td class="order-detail-link">
                            <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Xem đơn hàng
                                </button>
                                <ul class="dropdown-menu">
                                    ${product.order_links.map(order => `
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); showOrderDetail(${order.id})">
                                                Đơn hàng #${order.id}
                                            </a>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        </td>
                    </tr>
                `).join('') :
                '<tr><td colspan="6" style="text-align: center;">Không có dữ liệu trong khoảng thời gian này</td></tr>';
            
            if (products && products.length > 5) {
                productTable.closest('table').classList.add('scrollable-table');
          
                let scrollIndicator = document.getElementById('product-scroll-indicator');
                if (!scrollIndicator) {
                  scrollIndicator = document.createElement('div');
                  scrollIndicator.id = 'product-scroll-indicator';
                  scrollIndicator.className = 'scroll-indicator';
                  scrollIndicator.innerHTML = '<i class="fa-solid fa-angles-down"></i> Cuộn xuống để xem thêm';
                  productTable.closest('table').after(scrollIndicator);
                }
                scrollIndicator.style.display = 'block';
              } else {
      
                productTable.closest('table').classList.remove('scrollable-table');
                
                const scrollIndicator = document.getElementById('product-scroll-indicator');
                if (scrollIndicator) {
                  scrollIndicator.style.display = 'none';
                }
              }
        }
    }
    window.showOrderDetail = showOrderDetail;

    form.addEventListener('submit', function(event) {
      event.preventDefault();

      if (startDate.value > endDate.value) {
        showError('Ngày bắt đầu không thể lớn hơn ngày kết thúc');
        return;
      }
      
      const formData = new FormData(form);
      
      fetch('../php/analyze_data.php', {
        method: 'POST',
        body: formData
      })
      .then(response => { 
        if (!response.ok) {
          return response.json().then(err => Promise.reject(err));
        }
        return response.json();
      })
      .then(data => {
        if (!data.success) {
          throw new Error(data.error || 'Có lỗi xảy ra');
        }
 
        updateCustomerTable(data.customers);
  
        updateProductTable(data.products);
  
        updateStatistics(data);
        saveFilterValues(); 
      })
      .catch(error => {
        console.error('Error:', error);
        showError('Có lỗi xảy ra khi tải dữ liệu: ' + (error.error || error.message));
      });
    });
    restoreFilterValues();
    form.dispatchEvent(new Event('submit'));
  });