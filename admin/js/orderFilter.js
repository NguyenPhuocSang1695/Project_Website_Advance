document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const orderTableBody = document.getElementById('orderTableBody');
    const originalRows = [...orderTableBody.getElementsByTagName('tr')];

    filterButton.addEventListener('click', function() {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        const status = document.getElementById('orderStatus').value;
        const district = document.getElementById('district').value;

        const filteredRows = originalRows.filter(row => {
            let match = true;

            // Lọc theo trạng thái
            if (status !== 'all') {
                const statusCell = row.querySelector('td:nth-child(7) button');
                if (!statusCell.textContent.includes(status)) {
                    match = false;
                }
            }

            // Lọc theo quận/huyện
            if (district !== 'all') {
                const districtCell = row.querySelector('td:nth-child(8)');
                if (!districtCell.textContent.includes(district)) {
                    match = false;
                }
            }

            return match;
        });

        // Cập nhật bảng
        orderTableBody.innerHTML = '';
        filteredRows.forEach(row => {
            orderTableBody.appendChild(row.cloneNode(true));
        });
    });

    // Thêm chức năng tìm kiếm
    const searchBar = document.querySelector('.Search-bar');
    searchBar.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        const filteredRows = originalRows.filter(row => {
            const cells = row.getElementsByTagName('td');
            return Array.from(cells).some(cell => 
                cell.textContent.toLowerCase().includes(searchTerm)
            );
        });

        orderTableBody.innerHTML = '';
        filteredRows.forEach(row => {
            orderTableBody.appendChild(row.cloneNode(true));
        });
    });
}); 