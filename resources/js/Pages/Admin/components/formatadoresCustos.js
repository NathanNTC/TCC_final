export function formatarMoeda(valor, moeda = 'BRL') {
    const numero = Number(valor) || 0

    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: moeda,
    }).format(numero)
}

export function formatarNumero(valor) {
    return new Intl.NumberFormat('pt-BR').format(
        Number(valor) || 0
    )
}

export function formatarDuracao(valor) {
    const ms = Number(valor) || 0

    if (ms < 1000) {
        return `${Math.round(ms)} ms`
    }

    return `${(ms / 1000).toFixed(2).replace('.', ',')} s`
}

export function formatarData(valor) {
    if (!valor) return '—'

    const data = new Date(valor)

    if (Number.isNaN(data.getTime())) {
        return String(valor)
    }

    return data.toLocaleString('pt-BR')
}

export function formatarDataCurta(valor) {
    if (!valor) return '—'

    const data = new Date(valor)

    if (Number.isNaN(data.getTime())) {
        return String(valor)
    }

    return data.toLocaleDateString('pt-BR')
}

export function formatarPercentual(valor) {
    return `${(Number(valor) || 0).toFixed(1).replace('.', ',')}%`
}