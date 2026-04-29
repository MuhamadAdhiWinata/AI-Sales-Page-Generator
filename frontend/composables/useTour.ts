import { driver } from "driver.js";
import "driver.js/dist/driver.css";

export const useTour = () => {
  const startMainTour = () => {
    const driverObj = driver({
      showProgress: true,
      animate: true,
      stagePadding: 4,
      popoverClass: 'driverjs-dark-theme',
      steps: [
        { 
          element: '#create-page-btn', 
          popover: { 
            title: 'Buat Sales Page Baru', 
            description: 'Klik di sini untuk mulai membuat halaman penjualan pertama Anda dengan bantuan AI.', 
            side: "bottom", 
            align: 'start' 
          } 
        },
        { 
          element: '#product-form', 
          popover: { 
            title: 'Isi Detail Produk', 
            description: 'Berikan informasi lengkap mengenai produk Anda. Semakin detail, semakin bagus hasil AI-nya.', 
            side: "right", 
            align: 'start' 
          } 
        },
        { 
          element: '#template-selector', 
          popover: { 
            title: 'Pilih Style', 
            description: 'Pilih tema visual yang paling cocok dengan brand Anda (Classic, Neon, atau Pastel).', 
            side: "left", 
            align: 'start' 
          } 
        },
        { 
          element: '#generate-btn', 
          popover: { 
            title: 'Generate!', 
            description: 'Terakhir, klik generate dan biarkan AI bekerja untuk Anda selama beberapa detik.', 
            side: "top", 
            align: 'center' 
          } 
        },
        { 
          element: '#preview-iframe', 
          popover: { 
            title: 'Pratinjau Langsung', 
            description: 'Hasil akan muncul di sini. Anda bisa melihat desain dan konten secara real-time.', 
            side: "left", 
            align: 'start' 
          } 
        },
        { 
          element: '#edit-ai-panel', 
          popover: { 
            title: 'Sempurnakan dengan AI', 
            description: 'Kurang puas? Gunakan fitur "Edit with AI" untuk meminta perubahan spesifik menggunakan bahasa manusia.', 
            side: "left", 
            align: 'start' 
          } 
        },
      ]
    });

    driverObj.drive();
  };

  return {
    startMainTour
  };
};
