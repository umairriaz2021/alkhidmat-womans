import React, { createContext, useState, useContext } from 'react';

const CartContext = createContext();

export const CartProvider = ({ children }) => {

    const [cart, setCart] = useState({
        items: [],
        count: 0,
        totalAmount: 0
    });

    const addToCart = (item, quantity) => {

        setCart(prev => {

            const existingItem = prev.items.find(
                cartItem => cartItem.title === item.title
            );

            let updatedItems = [];

            if (existingItem) {

                updatedItems = prev.items.map(cartItem =>
                    cartItem.title === item.title
                        ? {
                            ...cartItem,
                            quantity,
                            total: quantity * item.amount
                        }
                        : cartItem
                );

            } else {

                updatedItems = [
                    ...prev.items,
                    {
                        ...item,
                        quantity,
                        total: quantity * item.amount
                    }
                ];
            }

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