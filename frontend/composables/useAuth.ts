export const useAuth = () => {
  const token = useCookie('auth_token')
  const user = useState('user', () => null)
  const config = useRuntimeConfig()

  const setToken = (newToken: string) => {
    token.value = newToken
  }

  const setUser = (newUser: any) => {
    user.value = newUser
  }

  const logout = async () => {
    try {
      await $fetch(`${config.public.apiUrl}/logout`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })
    } catch (e) {
      console.error('Logout failed', e)
    } finally {
      token.value = null
      user.value = null
      navigateTo('/login')
    }
  }

  const fetchUser = async () => {
    if (!token.value) return
    try {
      const data = await $fetch(`${config.public.apiUrl}/me`, {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })
      user.value = data
    } catch (e) {
      token.value = null
      user.value = null
    }
  }

  return {
    token,
    user,
    setToken,
    setUser,
    logout,
    fetchUser
  }
}
