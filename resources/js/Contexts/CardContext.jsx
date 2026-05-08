import React, { createContext, useState, useContext,useEffect } from 'react';

const CartContext = createContext();

export const CartProvider = ({ children }) => {

    const [cart, setCart] = useState(() => {
        const savedCart = sessionStorage.getItem('akf_cart');
        return savedCart ? JSON.parse(savedCart) : {
            items: [],
            count: 0,
            totalAmount: 0
        };
    });
   
    useEffect(() => {
        sessionStorage.setItem('akf_cart', JSON.stringify(cart));
    }, [cart]);

    const addToCart = (item, quantity) => {
    // Pehle check karein ke quantity number hai
    const qty = parseInt(quantity);

    setCart(prev => {
        let updatedItems;

        // AGAR QUANTITY 0 YA US SE KAM HAI: Item ko nikaal do
        if (qty <= 0) {
            updatedItems = prev.items.filter(
                cartItem => cartItem.title !== item.title
            );
        } else {
            const existingItem = prev.items.find(
                cartItem => cartItem.title === item.title
            );

            if (existingItem) {
                // Item exist karta hai toh update karo
                updatedItems = prev.items.map(cartItem =>
                    cartItem.title === item.title
                        ? {
                            ...cartItem,
                            quantity: qty,
                            total: qty * item.amount
                        }
                        : cartItem
                );
            } else {
                // Naya item add karo
                updatedItems = [
                    ...prev.items,
                    {
                        ...item,
                        quantity: qty,
                        total: qty * item.amount
                    }
                ];
            }
        }

        // Nayi calculations
        const totalAmount = updatedItems.reduce(
            (sum, item) => sum + item.total,
            0
        );

        const count = updatedItems.reduce(
            (sum, item) => sum + item.quantity,
            0
        );

        return {
            items: updatedItems,
            count,
            totalAmount
        };
    });
};

    return (
        <CartContext.Provider value={{ cart, addToCart }}>
            {children}
        </CartContext.Provider>
    );
};
/* @refresh reset */
export const useCart = () => useContext(CartContext);