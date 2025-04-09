document.addEventListener('DOMContentLoaded', () => {
    fetch('../php/sessionHandler.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Cập nhật tên và chức vụ
                document.querySelector('.name-employee p').textContent = data.fullname;
                document.querySelector('.position-employee p').textContent = data.role;
                
                // Cập nhật thông tin trong offcanvas
                document.querySelector('#offcanvasWithBothOptionsLabel').textContent = data.username;
                document.querySelector('#employee-displayname').textContent = data.fullname; 
                
                // Cập nhật ảnh đại diện
                const avatarImages = document.querySelectorAll('.avatar');
                const defaultAvatar = data.role === 'admin' 
                    ? '../../assets/images/admin.jpg' 
                    : '../../assets/images/sang.jpg';
                
                avatarImages.forEach(img => {
                    img.src = defaultAvatar;
                });

                localStorage.setItem('userInfo', JSON.stringify({
                    username: data.username,
                    fullname: data.fullname,
                    role: data.role,
                    avatar: defaultAvatar
                }));
            } else {
                alert('Bạn chưa đăng nhập!');
                window.location.href = '../index.php';
            }
        })
        .catch(error => {
            console.error('Lỗi khi kiểm tra trạng thái đăng nhập:', error);
            alert('Không thể kiểm tra trạng thái đăng nhập!');
        });
});

function logout() {
    fetch('../php/logout.php', { method: 'POST' })
        .then(() => {
            window.location.href = '../index.php';
        })
        .catch(error => {
            console.error('Lỗi khi đăng xuất:', error);
            alert('Không thể đăng xuất!');
        });
}

function loadPage(page) {
    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error('Không thể tải trang!');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('content').innerHTML = data;
        })
        .catch(error => {
            console.error('Lỗi khi tải trang:', error);
            alert('Không thể tải trang!');
        });
}