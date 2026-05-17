document.addEventListener("DOMContentLoaded", () => {
  const productGrid = document.getElementById("product-grid");
  const categoryList = document.getElementById("category-list");

  // 1. Đọc từ khóa tìm kiếm từ thanh URL
  const urlParams = new URLSearchParams(window.location.search);
  const initialSearchQuery = urlParams.get("search") || "";

  // Biến lưu trữ trạng thái hiện tại (Danh mục, Từ khóa)
  let currentCategoryId = null;
  let currentSearch = initialSearchQuery;

  // Hàm tải dữ liệu từ API
  async function loadProducts(categoryId = null, search = "", page = 1) {
    currentCategoryId = categoryId;
    currentSearch = search;

    try {
      productGrid.innerHTML =
        '<div class="col-span-full text-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-black mx-auto"></div></div>';

      let url = `/api/products?page=${page}`;
      if (categoryId !== null) url += `&category_id=${categoryId}`;
      if (search) url += `&search=${encodeURIComponent(search)}`;

      const response = await fetch(url);
      const result = await response.json();

      if (result.success) {
        renderProducts(result.data.products, search, result.data.pagination);
        renderCategories(result.data.categories, categoryId);
      } else {
        productGrid.innerHTML = `<div class="col-span-full text-center text-red-500 font-bold">${result.message}</div>`;
      }
    } catch (error) {
      console.error("Lỗi Fetch API:", error);
      productGrid.innerHTML =
        '<div class="col-span-full text-center text-red-500 font-bold">Lỗi kết nối máy chủ!</div>';
    }
  }

  function renderProducts(products, keyword = "", pagination = null) {
    if (products.length === 0) {
      let emptyMsg = keyword
        ? `Không tìm thấy sản phẩm cho từ khóa "${keyword}".`
        : "Không có sản phẩm nào.";
      productGrid.innerHTML = `<div class="col-span-full text-center text-gray-500 py-16">${emptyMsg}</div>`;
      return;
    }

    let html = "";
    products.forEach((product) => {
      const isSale = product.sale_price ? true : false;
      const priceHtml = isSale
        ? `<span class="text-black font-medium">${Number(product.sale_price).toLocaleString("vi-VN")}đ</span>
                   <span class="text-gray-400 line-through text-xs ml-2">${Number(product.price).toLocaleString("vi-VN")}đ</span>`
        : `<span class="text-black font-medium">${Number(product.price).toLocaleString("vi-VN")}đ</span>`;

      html += `
                <div class="group flex flex-col cursor-pointer">
                    <div class="relative aspect-[4/5] overflow-hidden bg-gray-50 mb-4">
                        
                        <div class="absolute top-0 left-0 z-10 flex flex-col p-3 gap-2">
                            ${product.stock < 1 ? '<span class="bg-gray-100 text-black text-[10px] uppercase font-bold px-2 py-1">Hết hàng</span>' : ""}
                            ${isSale && product.stock > 0 ? '<span class="bg-black text-white text-[10px] uppercase font-bold px-2 py-1">Sale</span>' : ""}
                        </div>
                        
                        <a href="/product/${product.slug}" class="block w-full h-full">
                            <img src="${product.thumbnail}" alt="${product.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </a>
                        
                        <div class="absolute bottom-0 inset-x-0 p-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <button onclick="event.preventDefault(); addToCart(${product.id})" ${product.stock < 1 ? "disabled" : ""}
                                class="w-full ${product.stock < 1 ? "bg-gray-200 text-gray-400 cursor-not-allowed" : "bg-black text-white hover:bg-gray-800"} py-3 text-xs font-bold uppercase tracking-widest transition-colors rounded-none">
                                Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-col text-center">
                        <a href="/product/${product.slug}">
                            <h3 class="text-sm font-normal text-black mb-1 line-clamp-1 leading-relaxed">${product.name}</h3>
                        </a>
                        <div class="text-sm leading-relaxed">
                            ${priceHtml}
                        </div>
                    </div>
                </div>
            `;
    });

    // Phân Trang Minimal
    if (pagination && pagination.total_pages > 1) {
      html += `<div class="col-span-full flex justify-center items-center space-x-6 mt-12 pt-8">`;
      for (let i = 1; i <= pagination.total_pages; i++) {
        const isActive = i === pagination.current_page;
        html += `
                    <button onclick="goToPage(${i})" 
                        class="text-sm transition-all ${isActive ? "text-black font-bold underline underline-offset-8" : "text-gray-400 hover:text-black"}">
                        ${i}
                    </button>`;
      }
      html += `</div>`;
    }
    productGrid.innerHTML = html;
  }

  function renderCategories(categories, activeId) {
    let html = `
            <li>
                <button onclick="filterCategory(null)" class="text-sm uppercase tracking-widest transition-colors pb-1 border-b-2 ${activeId === null ? "text-black border-black font-medium" : "text-gray-400 border-transparent hover:text-black"}">
                    Tất cả
                </button>
            </li>`;

    html += categories
      .map(
        (c) => `
            <li>
                <button onclick="filterCategory(${c.id})" class="text-sm uppercase tracking-widest transition-colors pb-1 border-b-2 ${activeId == c.id ? "text-black border-black font-medium" : "text-gray-400 border-transparent hover:text-black"}">
                    ${c.name}
                </button>
            </li>
        `,
      )
      .join("");
    categoryList.innerHTML = html;
  }
  // --- CÁC HÀM GLOBAL (WINDOW) ---

  // Hàm bấm chọn danh mục
  window.filterCategory = (id) => {
    loadProducts(id, currentSearch, 1);
  };

  // Hàm chuyển trang
  window.goToPage = (page) => {
    loadProducts(currentCategoryId, currentSearch, page);
    // Tự động cuộn mượt mà lên khu vực sản phẩm khi chuyển trang
    document
      .getElementById("shop-section")
      .scrollIntoView({ behavior: "smooth" });
  };

  // Hàm thêm vào giỏ hàng
  window.addToCart = async function (productId) {
    try {
      const response = await fetch("/api/cart/add", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, quantity: 1 }),
      });

      const result = await response.json();

      if (result.success) {
        if (typeof showToast === "function") showToast(result.message, true);

        const badge = document.getElementById("cart-badge");
        if (badge) {
          badge.textContent = result.data.cart_count;
          badge.classList.add("scale-150");
          setTimeout(() => badge.classList.remove("scale-150"), 300);
        }
      } else {
        if (typeof showToast === "function") showToast(result.message, false);
      }
    } catch (error) {
      console.error("Lỗi khi thêm vào giỏ:", error);
      if (typeof showToast === "function")
        showToast("Lỗi kết nối đến máy chủ.", false);
    }
  };

  // Khởi chạy lấy dữ liệu lần đầu
  loadProducts(null, initialSearchQuery, 1);
});
