
// Hàm mã hóa password 
function hashPassword(password) {
    let hash = 0;
    for (let i = 0; i < password.length; i++) {
        const char = password.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    return hash.toString(16);
}

// Dữ liệu mẫu người dùng và lịch sử đơn hàng
let users = [
    { 
        id: 1, 
        fullName: "Nguyễn Thanh Tùng", 
        phone: "9999 999 999", 
        email: "nguyentb@gmail.com", 
        password: hashPassword("123456"), 
        gender: "Nam", 
        hometown: "Thái Bình", 
        totalOrders: 5, 
        type: "Kim cương", 
        status: "active", 
        img: "../image/sontung.webp",
        orders: [
            { id: "#123123", productImg: "../../assets/images/CAY13.jpg", amount: 500000, date: "2025-03-20" },
            { id: "#123124", productImg: "../../assets/images/CAY14.jpg", amount: 300000, date: "2025-03-19" },
            { id: "#123125", productImg: "../../assets/images/CAY12.jpg", amount: 700000, date: "2025-03-18" }
        ]
    },
    { 
        id: 2, 
        fullName: "Hiếu 2nd", 
        phone: "8888 888 888", 
        email: "hieu2nd@gmail.com", 
        password: hashPassword("abcdef"), 
        gender: "Nam", 
        hometown: "Hồ Chí Minh", 
        totalOrders: 3, 
        type: "Vàng", 
        status: "active", 
        img: "../image/hth.webp",
        orders: [
            { id: "#123126", productImg: "../image/product1.webp", amount: 200000, date: "2025-03-21" }
        ]
    },
    { 
        id: 3, 
        fullName: "Diễm", 
        phone: "7777 777 777", 
        email: "diem@gmail.com", 
        password: hashPassword("xyz789"), 
        gender: "Nữ", 
        hometown: "Đồng Nai", 
        totalOrders: 2, 
        type: "Bạc", 
        status: "active", 
        img: "../image/baolam.jpg",
        orders: [
            { id: "#123127", productImg: "../image/product1.webp", amount: 450000, date: "2025-03-20" }
        ]
    }
];

// Hiển thị danh sách người dùng dạng bảng
function renderUsers(filteredUsers = users) {
    const userList = document.getElementById('userList');
    userList.innerHTML = '';
    filteredUsers.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = user.status === 'locked' ? 'locked' : '';
        tr.innerHTML = `
            <td>${user.id}</td>
            <td>${user.fullName}</td>
            <td>${user.phone}</td>
            <td>${user.email}</td>
            <td>${user.status === 'locked' ? 'Đã khoá' : 'Hoạt động'}</td>
        `;
        tr.onclick = () => showUserDetails(user.id);
        userList.appendChild(tr);
    });
}

// Tìm kiếm người dùng
function searchUsers() {
    const searchTerm = document.querySelector('.search-bar-customer').value.toLowerCase();
    const filteredUsers = users.filter(u => 
        u.fullName.toLowerCase().includes(searchTerm) || 
        u.phone.includes(searchTerm) || 
        u.email.toLowerCase().includes(searchTerm)
    );
    renderUsers(filteredUsers);
}

// Hiển thị chi tiết người dùng và lịch sử 5 ngày gần đây
function showUserDetails(userId) {
    const user = users.find(u => u.id === userId);
    const detailsContent = document.getElementById('userDetailsContent');
    const currentDate = new Date("2025-03-22");
    const fiveDaysAgo = new Date(currentDate);
    fiveDaysAgo.setDate(currentDate.getDate() - 5);

    const recentOrders = user.orders.filter(order => {
        const orderDate = new Date(order.date);
        return orderDate >= fiveDaysAgo && orderDate <= currentDate;
    });

    detailsContent.innerHTML = `
        <h3>Thông tin người dùng</h3>
        <div class="form-group">
            <label>Họ và tên:</label>
            <p>${user.fullName}</p>
        </div>
        <div class="form-group">
            <label>Số điện thoại:</label>
            <p>${user.phone}</p>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <p>${user.email}</p>
        </div>
        <div class="form-group">
            <label>Giới tính:</label>
            <p>${user.gender}</p>
        </div>
        <div class="form-group">
            <label>Quê quán:</label>
            <p>${user.hometown}</p>
        </div>
        <div class="form-group">
            <label>Tổng đơn:</label>
            <p>${user.totalOrders}</p>
        </div>
        <div class="form-group">
            <label>Loại khách hàng:</label>
            <p>${user.type}</p>
        </div>
        <div class="form-group">
            <label>Trạng thái:</label>
            <p>${user.status === 'locked' ? 'Đã khoá' : 'Hoạt động'}</p>
        </div>
        <div class="history-list">
            <h4>Lịch sử đơn hàng (5 ngày gần đây)</h4>
            ${recentOrders.length > 0 ? recentOrders.map(order => `
                <div class="history-item">
                    <img src="${order.productImg}" style="width: 50px; height: 50px; border: 3px solid #35635A;">
                    <p>${order.id} - ${order.amount.toLocaleString()} VND - ${order.date}</p>
                </div>
            `).join('') : '<p>Không có đơn hàng nào trong 5 ngày gần đây.</p>'}
        </div>
        <div class="form-actions">
            <button onclick="showEditUserPopup(${user.id})" class="save-btn">Chỉnh sửa</button>
            <button onclick="toggleLockUser(${user.id})" class="save-btn" style="background: ${user.status === 'locked' ? '#28A745' : '#D95E5E'}">${user.status === 'locked' ? 'Mở khoá' : 'Khoá'}</button>
            <button onclick="closeUserDetailsPopup()" class="cancel-btn">Đóng</button>
        </div>
    `;
    document.getElementById('userDetailsOverlay').classList.add('active');
}

function closeUserDetailsPopup() {
    document.getElementById('userDetailsOverlay').classList.remove('active');
}

// Hiển thị popup thêm người dùng
function showAddUserPopup() {
    document.getElementById('addUserOverlay').classList.add('active');
}

function closeAddUserPopup() {
    if (confirm('Bạn có muốn hủy thêm người dùng này?')) {
        document.getElementById('addUserOverlay').classList.remove('active');
    }
}

function confirmAddUser() {
    if (confirm('Bạn có chắc chắn muốn thêm người dùng này?')) {
        const password = document.getElementById('addPassword').value;
        const newUser = {
            id: users.length + 1,
            fullName: document.getElementById('addFullName').value,
            phone: document.getElementById('addPhone').value,
            email: document.getElementById('addEmail').value,
            password: hashPassword(password),
            gender: document.getElementById('addGender').value,
            hometown: document.getElementById('addHometown').value,
            totalOrders: 0,
            type: "Bạc",
            status: "active",
            img: "../image/default-customer.jpg",
            orders: []
        };
        users.push(newUser);
        alert('Đã thêm người dùng thành công!');
        document.getElementById('addUserOverlay').classList.remove('active');
        renderUsers();
    }
}

// Hiển thị popup chỉnh sửa người dùng
function showEditUserPopup(userId) {
    const user = users.find(u => u.id === userId);
    const editContent = document.getElementById('editUserContent');
    editContent.innerHTML = `
        <h3>Chỉnh sửa thông tin</h3>
        <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" id="editFullName-${userId}" value="${user.fullName}">
        </div>
        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" id="editPhone-${userId}" value="${user.phone}">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" id="editEmail-${userId}" value="${user.email}">
        </div>
        <div class="form-group">
            <label>Mật khẩu mới (để trống nếu không đổi):</label>
            <input type="password" id="editPassword-${userId}">
        </div>
        <div class="form-group">
            <label>Giới tính:</label>
            <select id="editGender-${userId}">
                <option value="Nam" ${user.gender === 'Nam' ? 'selected' : ''}>Nam</option>
                <option value="Nữ" ${user.gender === 'Nữ' ? 'selected' : ''}>Nữ</option>
            </select>
        </div>
        <div class="form-group">
            <label>Quê quán:</label>
            <input type="text" id="editHometown-${userId}" value="${user.hometown}">
        </div>
        <div class="form-actions">
            <button onclick="confirmEditUser(${userId})" class="save-btn">Lưu</button>
            <button onclick="closeEditUserPopup()" class="cancel-btn">Hủy</button>
        </div>
    `;
    document.getElementById('editUserOverlay').classList.add('active');
}

function closeEditUserPopup() {
    document.getElementById('editUserOverlay').classList.remove('active');
}

function confirmEditUser(userId) {
    if (confirm('Bạn có chắc chắn muốn lưu thay đổi?')) {
        const user = users.find(u => u.id === userId);
        user.fullName = document.getElementById(`editFullName-${userId}`).value;
        user.phone = document.getElementById(`editPhone-${userId}`).value;
        user.email = document.getElementById(`editEmail-${userId}`).value;
        const newPassword = document.getElementById(`editPassword-${userId}`).value;
        if (newPassword) user.password = hashPassword(newPassword);
        user.gender = document.getElementById(`editGender-${userId}`).value;
        user.hometown = document.getElementById(`editHometown-${userId}`).value;
        alert('Đã lưu thay đổi thành công!');
        document.getElementById('editUserOverlay').classList.remove('active');
        renderUsers();
    }
}

// Khoá/Mở khoá người dùng
function toggleLockUser(userId) {
    const user = users.find(u => u.id === userId);
    if (user.status === 'active' && confirm('Bạn có chắc chắn muốn khoá người dùng này?')) {
        user.status = 'locked';
        alert('Đã khoá người dùng thành công!');
    } else if (user.status === 'locked' && confirm('Bạn có chắc chắn muốn mở khoá người dùng này?')) {
        user.status = 'active';
        alert('Đã mở khoá người dùng thành công!');
    }
    document.getElementById('userDetailsOverlay').classList.remove('active');
    renderUsers();
}

// Khởi tạo
document.addEventListener('DOMContentLoaded', () => {
    renderUsers();
});
