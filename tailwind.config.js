/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './frontend/app/**/*.php', // Memindai semua file PHP di dalam folder admin dan pegawai
    './frontend/**/*.php',     // Memindai semua file PHP di folder frontend
    './frontend/app/admin/**/*.{php}',
    './**/*.php'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};


