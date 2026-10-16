import { ref, onMounted } from 'vue'

const isDark = ref(false)

function aplicarTema(dark) {
    isDark.value = dark

    if (dark) {
        document.documentElement.classList.add('dark')
        localStorage.setItem('theme', 'dark')
    } else {
        document.documentElement.classList.remove('dark')
        localStorage.setItem('theme', 'light')
    }
}

// Inicializa o estado lendo do localStorage ou das preferências do sistema
function inicializarTema() {
    if (typeof window === 'undefined') return

    const temaSalvo = localStorage.getItem('theme')

    if (temaSalvo === 'dark') {
        aplicarTema(true)
    } else if (temaSalvo === 'light') {
        aplicarTema(false)
    } else {
        // Se não houver preferências salvas, respeita a preferência do sistema operacional
        const prefereEscuro = window.matchMedia('(prefers-color-scheme: dark)').matches
        aplicarTema(prefereEscuro)
    }
}

export function useDarkMode() {
    onMounted(() => {
        // Verifica o estado atual da classe no HTML ao montar o componente
        const temClasseDark = document.documentElement.classList.contains('dark')
        const temaSalvo = localStorage.getItem('theme')

        if (temaSalvo) {
            isDark.value = temaSalvo === 'dark'
        } else {
            isDark.value = temClasseDark
        }
    })

    function alternarTema() {
        aplicarTema(!isDark.value)
    }

    return {
        isDark,
        alternarTema,
        inicializarTema,
    }
}