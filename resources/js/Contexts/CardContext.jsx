import React, { createContext, useContext, useState, useEffect } from 'react';

const CardContext = createContext();

export const CardProvider = ({ children }) => {
    const [cartItems, setCartItems] = useState(() => {
        try {
            const savedCart = localStorage.getItem('app_cart_items');
            return savedCart ? JSON.parse(savedCart) : [];
        } catch (e) {
            return [];
        }
    });

    useEffect(() => {
        try {
            localStorage.setItem('app_cart_items', JSON.stringify(cartItems));
        } catch (e) {
            console.error('LocalStorage error:', e);
        }
    }, [cartItems]);

    // Format items with fallbacks
    const formattedItems = (cartItems || []).map(item => {
        const qty = item.quantity || 1;
        const price = parseFloat(item.price || item.amount || item.total || 0);
        return {
            ...item,
            id: item.id || item.title,
            title: item.title || 'Custom Donation',
            price: price,
            quantity: qty,
            total: price * qty
        };
    });

    const totalAmount = formattedItems.reduce((sum, item) => sum + item.total, 0);
    const count = formattedItems.reduce((sum, item) => sum + item.quantity, 0);

    const cart = {
        items: formattedItems,
        totalAmount: totalAmount,
        count: count
    };

    // 1. Add relative quantity (Used in DonationPage / Appeals)
    const addToCart = (itemToAdd, qtyToAdd = 1) => {
        if (!itemToAdd || qtyToAdd <= 0) return;

        // Dynamic Title & ID Resolution
        const itemTitle = itemToAdd.title || itemToAdd.name || 'Custom Donation';
        const itemId = itemToAdd.id || itemTitle;
        const itemPrice = parseFloat(itemToAdd.amount || itemToAdd.price || 0);

        setCartItems(prevItems => {
            const safePrev = prevItems || [];
            
            // Match based on ID OR Title
            const existingIndex = safePrev.findIndex(
                item => (item.id && String(item.id) === String(itemId)) || item.title === itemTitle
            );

            if (existingIndex > -1) {
                return safePrev.map((item, idx) => {
                    if (idx === existingIndex) {
                        const newQty = item.quantity + qtyToAdd;
                        return {
                            ...item,
                            quantity: newQty,
                            price: itemPrice > 0 ? itemPrice : item.price,
                            total: (itemPrice > 0 ? itemPrice : item.price) * newQty
                        };
                    }
                    return item;
                });
            } else {
                const newItem = {
                    id: itemId,
                    title: itemTitle,
                    price: itemPrice,
                    amount: itemPrice,
                    quantity: qtyToAdd,
                    total: itemPrice * qtyToAdd,
                    isCustom: itemToAdd.isCustom || false
                };
                return [...safePrev, newItem];
            }
        });
    };

    // 2. Update EXACT quantity (Used in DonationSummary +/- buttons)
    const updateQuantity = (itemToUpdate, newQty) => {
        if (!itemToUpdate) return;
        const itemId = itemToUpdate.id || itemToUpdate.title;

        setCartItems(prevItems => {
            const safePrev = prevItems || [];
            
            // If Qty is 0 or less, REMOVE item from cart
            if (newQty <= 0) {
                return safePrev.filter(
                    item => item.id !== itemId && item.title !== itemToUpdate.title
                );
            }

            return safePrev.map(item => {
                if (item.id === itemId || item.title === itemToUpdate.title) {
                    const unitPrice = parseFloat(item.price || item.amount || 0);
                    return {
                        ...item,
                        quantity: newQty,
                        total: unitPrice * newQty
                    };
                }
                return item;
            });
        });
    };

    // 3. Directly REMOVE item from cart
    const removeFromCart = (itemToRemove) => {
        if (!itemToRemove) return;
        const itemId = itemToRemove.id || itemToRemove.title;

        setCartItems(prevItems => {
            return (prevItems || []).filter(
                item => item.id !== itemId && item.title !== itemToRemove.title
            );
        });
    };

    // 4. UPDATED Dynamic Custom/Appeal Donation Handler
    const addCustomDonation = (amount, title = 'Custom Donation', customId = null) => {
        const numericAmount = parseFloat(amount);
        if (isNaN(numericAmount) || numericAmount <= 0) return false;

        const donationTitle = title || 'Custom Donation';
        const itemId = customId || donationTitle;

        setCartItems(prevItems => {
            const safePrev = prevItems || [];
            
            // Match specifically on TITLE so 'Palestine Funds' stays separate from 'Custom Donation'
            const existingIndex = safePrev.findIndex(
                item => item.title.toLowerCase() === donationTitle.toLowerCase() || item.id === itemId
            );

            if (existingIndex > -1) {
                return safePrev.map((item, idx) => {
                    if (idx === existingIndex) {
                        const currentAmount = parseFloat(item.price || item.amount || 0);
                        const newTotalAmount = currentAmount + numericAmount;
                        return {
                            ...item,
                            price: newTotalAmount,
                            amount: newTotalAmount,
                            total: newTotalAmount
                        };
                    }
                    return item;
                });
            } else {
                const customItem = {
                    id: itemId || `donation-${Date.now()}`,
                    title: donationTitle,
                    price: numericAmount,
                    amount: numericAmount,
                    quantity: 1,
                    total: numericAmount,
                    isCustom: true
                };
                return [...safePrev, customItem];
            }
        });

        return true;
    };

    const clearCart = () => {
        setCartItems([]);
        localStorage.removeItem('app_cart_items');
    };

    const getCartTotal = () => totalAmount;

    return (
        <CardContext.Provider value={{
            cart,
            cartItems: formattedItems,
            items: formattedItems,
            count,
            totalAmount,
            addToCart,
            updateQuantity,
            removeFromCart,
            addCustomDonation,
            clearCart,
            getCartTotal
        }}>
            {children}
        </CardContext.Provider>
    );
};

export const useCardContext = () => {
    const context = useContext(CardContext);
    if (!context) {
        return {
            cart: { items: [], totalAmount: 0, count: 0 },
            cartItems: [],
            items: [],
            count: 0,
            totalAmount: 0,
            addToCart: () => {},
            updateQuantity: () => {},
            removeFromCart: () => {},
            addCustomDonation: () => {},
            clearCart: () => {},
            getCartTotal: () => 0
        };
    }
    return context;
};

export const useCart = useCardContext;
export { CardProvider as CartProvider };