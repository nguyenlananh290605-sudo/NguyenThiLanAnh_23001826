const app = Vue.createApp({
    data() {
        return {
            sanPham: [],
            bieuMau: { name: '', price: '', stock: '' },
            idDangSua: null,
            dangXuLy: false,
            daTai: false,
            loi: '',
            thongBao: ''
        };
    },

    methods: {
        dinhDangTien(gia) {
            return gia.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        },

        // Một hàm chung để gọi API và đọc thông báo lỗi JSON từ PHP.
        async goiApi(method = 'GET', duLieu = null, id = null) {
            const url = './api/products.php' + (id === null ? '' : '?id=' + encodeURIComponent(id));
            const options = { method, cache: 'no-store' };
            if (duLieu !== null) {
                options.headers = { 'Content-Type': 'application/json' };
                options.body = JSON.stringify(duLieu);
            }
            let response;
            try {
                response = await fetch(url, options);
            } catch {
                throw new Error('Không kết nối được API. Hãy kiểm tra Apache và kết nối mạng.');
            }
            let result;
            try {
                result = await response.json();
            } catch {
                throw new Error('API không trả về JSON hợp lệ. Hãy kiểm tra máy chủ PHP.');
            }
            if (!response.ok || !result || result.success !== true) {
                throw new Error(result?.message || 'Không thể xử lý yêu cầu.');
            }
            return result;
        },

        async taiSanPham() {
            const result = await this.goiApi();
            if (!Array.isArray(result.data)) throw new Error('Danh sách sản phẩm từ API không hợp lệ.');
            this.sanPham = result.data;
            this.daTai = true;
        },

        async lamMoi() {
            if (this.dangXuLy) return;
            this.dangXuLy = true;
            this.loi = '';
            this.thongBao = '';
            try {
                await this.taiSanPham();
            } catch (error) {
                this.loi = error.message;
            } finally {
                this.dangXuLy = false;
            }
        },

        chonSua(sp) {
            if (this.dangXuLy) return;
            this.idDangSua = sp.id;
            // Sao chép để nhập trên form không sửa trực tiếp dữ liệu trong bảng.
            this.bieuMau = { name: sp.name, price: sp.price, stock: sp.stock };
            this.loi = '';
            this.thongBao = '';
        },

        huySua() {
            this.idDangSua = null;
            this.bieuMau = { name: '', price: '', stock: '' };
            this.loi = '';
        },

        async luuSanPham() {
            if (this.dangXuLy) return;
            this.loi = '';
            this.thongBao = '';
            const name = this.bieuMau.name.trim();
            const price = Number(this.bieuMau.price);
            const stock = Number(this.bieuMau.stock);
            if ([...name].length < 1 || [...name].length > 100) {
                this.loi = 'Tên sản phẩm phải có từ 1 đến 100 ký tự.';
                return;
            }
            if (!Number.isInteger(price) || price <= 0 || price > 2147483647) {
                this.loi = 'Đơn giá phải là số nguyên lớn hơn 0 và không vượt quá 2.147.483.647.';
                return;
            }
            if (String(this.bieuMau.stock).trim() === '' || !Number.isInteger(stock) || stock < 0 || stock > 2147483647) {
                this.loi = 'Tồn kho phải là số nguyên không âm và không vượt quá 2.147.483.647.';
                return;
            }

            this.dangXuLy = true;
            try {
                const method = this.idDangSua === null ? 'POST' : 'PUT';
                const result = await this.goiApi(method, { name, price, stock }, this.idDangSua);
                this.huySua();
                this.thongBao = result.message;
                // Lưu đã thành công: nếu tải danh sách lỗi, không gửi lại thao tác lưu.
                try {
                    await this.taiSanPham();
                } catch (error) {
                    this.loi = 'Đã lưu nhưng chưa tải lại được danh sách. Bấm “Tải lại danh sách”. ' + error.message;
                }
            } catch (error) {
                this.loi = error.message;
            } finally {
                this.dangXuLy = false;
            }
        },

        async xoaSanPham(sp) {
            if (this.dangXuLy || !window.confirm(`Bạn có chắc muốn xóa sản phẩm "${sp.name}"?`)) return;
            this.dangXuLy = true;
            this.loi = '';
            this.thongBao = '';
            try {
                const result = await this.goiApi('DELETE', null, sp.id);
                if (this.idDangSua === sp.id) this.huySua();
                this.thongBao = result.message;
                try {
                    await this.taiSanPham();
                } catch (error) {
                    this.loi = 'Đã xóa nhưng chưa tải lại được danh sách. Bấm “Tải lại danh sách”. ' + error.message;
                }
            } catch (error) {
                this.loi = error.message;
            } finally {
                this.dangXuLy = false;
            }
        }
    },

    mounted() {
        this.lamMoi();
    }
});

app.mount('#app');
