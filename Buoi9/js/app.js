const app = Vue.createApp({
    data() { return {
        sanPham: [], bieuMau: { name: '', price: '', stock: '' }, idDangSua: null,
        trang: 1, tongTrang: 1, tong: 0, dangXuLy: false, daTai: false,
        loi: '', thongBao: '', fileAnh: null, anhXemTruoc: '', anhCu: '', uploadCho: null
    }; },
    methods: {
        dinhDangTien(gia) { return gia.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }); },
        async goiApi(method = 'GET', data = null, id = null, page = this.trang) {
            const query = method === 'GET' ? `?page=${page}&limit=5` : (id === null ? '' : `?id=${id}`);
            const options = { method, cache: 'no-store' };
            if (data !== null) { options.headers = { 'Content-Type': 'application/json' }; options.body = JSON.stringify(data); }
            let response;
            try { response = await fetch('./api/products.php' + query, options); }
            catch { throw new Error('Không kết nối được API. Hãy kiểm tra Apache.'); }
            let result;
            try { result = await response.json(); } catch { throw new Error('API không trả về JSON hợp lệ.'); }
            if (!response.ok || !result.success) throw new Error(result.message || 'Không thể xử lý yêu cầu.');
            return result;
        },
        async taiSanPham(page = this.trang) {
            const result = await this.goiApi('GET', null, null, page);
            this.sanPham = result.data; this.trang = result.pagination.page;
            this.tongTrang = result.pagination.total_pages; this.tong = result.pagination.total; this.daTai = true;
        },
        async lamMoi(page = this.trang) {
            if (this.dangXuLy) return;
            this.dangXuLy = true; this.loi = '';
            try { await this.taiSanPham(page); } catch (error) { this.loi = error.message; }
            finally { this.dangXuLy = false; }
        },
        chonAnh(event) {
            const file = event.target.files[0];
            if (this.anhXemTruoc) URL.revokeObjectURL(this.anhXemTruoc);
            this.fileAnh = null; this.anhXemTruoc = ''; this.loi = '';
            if (!file) return;
            if (!/\.(jpg|jpeg|png)$/i.test(file.name) || !['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024 || file.size === 0) {
                this.loi = 'Chọn ảnh JPG, JPEG hoặc PNG có dung lượng tối đa 2 MB.'; event.target.value = ''; return;
            }
            this.fileAnh = file; this.anhXemTruoc = URL.createObjectURL(file);
        },
        xoaAnhChon() {
            if (this.anhXemTruoc) URL.revokeObjectURL(this.anhXemTruoc);
            this.anhXemTruoc = ''; this.fileAnh = null;
            if (this.$refs.image) this.$refs.image.value = '';
        },
        chonSua(sp) {
            if (this.dangXuLy || this.uploadCho) return;
            this.xoaAnhChon(); this.idDangSua = sp.id; this.anhCu = sp.image || '';
            this.bieuMau = { name: sp.name, price: sp.price, stock: sp.stock }; this.loi = ''; this.thongBao = '';
        },
        huySua() {
            this.xoaAnhChon(); this.idDangSua = null; this.anhCu = '';
            this.bieuMau = { name: '', price: '', stock: '' };
        },
        async upload(id, file) {
            const form = new FormData(); form.append('id', id); form.append('image', file);
            let response;
            try { response = await fetch('./api/upload.php', { method: 'POST', body: form }); }
            catch { throw new Error('Không kết nối được API upload.'); }
            let result;
            try { result = await response.json(); } catch { throw new Error('API upload không trả về JSON hợp lệ.'); }
            if (!response.ok || !result.success) throw new Error(result.message || 'Upload thất bại.');
            return result;
        },
        async thuLaiUpload() {
            if (!this.uploadCho || this.dangXuLy) return;
            this.dangXuLy = true; this.loi = '';
            try {
                await this.upload(this.uploadCho.id, this.uploadCho.file);
                this.uploadCho = null; this.huySua(); this.thongBao = 'Upload ảnh thành công.';
                try { await this.taiSanPham(); } catch (error) { this.loi = 'Ảnh đã lưu, nhưng không tải lại được danh sách: ' + error.message; }
            } catch (error) { this.loi = 'Thông tin sản phẩm đã lưu. Upload ảnh thất bại: ' + error.message; }
            finally { this.dangXuLy = false; }
        },
        async luuSanPham() {
            if (this.dangXuLy || this.uploadCho) return;
            this.loi = ''; this.thongBao = '';
            const name = this.bieuMau.name.trim(), price = Number(this.bieuMau.price), stock = Number(this.bieuMau.stock);
            if ([...name].length < 1 || [...name].length > 100) { this.loi = 'Tên phải có từ 1 đến 100 ký tự.'; return; }
            if (!Number.isInteger(price) || price <= 0 || price > 2147483647) { this.loi = 'Đơn giá phải là số nguyên dương hợp lệ.'; return; }
            if (String(this.bieuMau.stock).trim() === '' || !Number.isInteger(stock) || stock < 0 || stock > 2147483647) { this.loi = 'Tồn kho phải là số nguyên không âm hợp lệ.'; return; }
            this.dangXuLy = true;
            const laThem = this.idDangSua === null;
            try {
                const result = await this.goiApi(laThem ? 'POST' : 'PUT', { name, price, stock }, this.idDangSua);
                this.thongBao = result.message;
                const targetPage = laThem ? 1 : this.trang;
                if (this.fileAnh) {
                    this.uploadCho = { id: result.data.id, file: this.fileAnh };
                    try { await this.upload(this.uploadCho.id, this.uploadCho.file); this.uploadCho = null; this.huySua(); this.thongBao += ' Upload ảnh thành công.'; }
                    catch (error) { this.loi = 'Thông tin sản phẩm đã lưu. Upload ảnh thất bại: ' + error.message; }
                } else this.huySua();
                try { await this.taiSanPham(targetPage); } catch (error) { this.loi += ' Không tải lại được danh sách: ' + error.message; }
            } catch (error) { this.loi = error.message; }
            finally { this.dangXuLy = false; }
        },
        async xoaSanPham(sp) {
            if (this.dangXuLy || this.uploadCho || !confirm(`Bạn có chắc muốn xóa sản phẩm "${sp.name}"?`)) return;
            this.dangXuLy = true; this.loi = ''; this.thongBao = '';
            try {
                const result = await this.goiApi('DELETE', null, sp.id);
                if (this.idDangSua === sp.id) this.huySua(); this.thongBao = result.message;
                try { await this.taiSanPham(); } catch (error) { this.loi = 'Đã xóa nhưng không tải lại được danh sách: ' + error.message; }
            } catch (error) { this.loi = error.message; } finally { this.dangXuLy = false; }
        }
    }, mounted() { this.lamMoi(); }
});
app.mount('#app');
