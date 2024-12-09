import mysql.connector
from mysql.connector import Error
import hashlib
import random
from datetime import datetime, timedelta

def create_connection():
    """Membuat koneksi ke database MySQL"""
    try:
        connection = mysql.connector.connect(
            host='localhost',  # Ganti dengan host MySQL yang sesuai
            database='db_tool',  # Ganti dengan nama database yang sesuai
            user='root',  # Ganti dengan username MySQL yang sesuai
            password=''  # Ganti dengan password MySQL yang sesuai
        )
        if connection.is_connected():
            print("Berhasil terhubung ke database")
        return connection
    except Error as e:
        print(f"Error: {e}")
        return None

def create_tables(connection):
    """Membuat tabel users, tools, dan logs"""
    cursor = connection.cursor()

    # SQL untuk membuat tabel users
    create_users_table = """
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    """
    
    # SQL untuk membuat tabel tools (menambahkan part_number)
    create_tools_table = """
    CREATE TABLE IF NOT EXISTS tools (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        part_number VARCHAR(255) NOT NULL UNIQUE,
        description TEXT,
        is_borrowed BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    """
    
    # SQL untuk membuat tabel logs (timestamp diganti jadi string)
    create_logs_table = """
    CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        borrower_name VARCHAR(255) NOT NULL,
        tool_id INT,
        timestamp VARCHAR(255) NOT NULL,
        calibration TEXT,
        aircraft_reg VARCHAR(255),
        status VARCHAR(255) DEFAULT 'Tersedia',
        FOREIGN KEY (tool_id) REFERENCES tools(id)
    );
    """

    # Menjalankan query untuk membuat tabel
    try:
        cursor.execute(create_users_table)
        cursor.execute(create_tools_table)
        cursor.execute(create_logs_table)
        connection.commit()
        print("Tabel berhasil dibuat.")
    except Error as e:
        print(f"Error saat membuat tabel: {e}")

def drop_tables(connection):
    """Menjatuhkan (drop) semua tabel yang ada"""
    cursor = connection.cursor()

    # Menonaktifkan pemeriksaan foreign key sementara
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
    
    # SQL untuk drop tabel users, tools, dan logs
    drop_users_table = "DROP TABLE IF EXISTS users;"
    drop_tools_table = "DROP TABLE IF EXISTS tools;"
    drop_logs_table = "DROP TABLE IF EXISTS logs;"
    
    try:
        cursor.execute(drop_users_table)
        cursor.execute(drop_tools_table)
        cursor.execute(drop_logs_table)
        connection.commit()
        print("Tabel berhasil dihapus.")
    except Error as e:
        print(f"Error saat menghapus tabel: {e}")
    
    # Mengaktifkan kembali pemeriksaan foreign key
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")

def create_admin_user(connection):
    """Membuat akun admin pertama kali dengan password yang di-hash MD5"""
    cursor = connection.cursor()
    
    # Mengecek apakah sudah ada user admin
    cursor.execute("SELECT COUNT(*) FROM users WHERE role = 'admin'")
    result = cursor.fetchone()
    
    if result[0] == 0:  # Jika belum ada user admin
        insert_admin_user = """
        INSERT INTO users (username, password, role)
        VALUES (%s, %s, %s);
        """
        # Meng-hash password dengan MD5
        hashed_password = hashlib.md5('admin123'.encode()).hexdigest()
        
        cursor.execute(insert_admin_user, ('admin', hashed_password, 'admin'))
        connection.commit()
        print("Akun admin berhasil dibuat.")
    else:
        print("Akun admin sudah ada.")

def insert_tools_data(connection):
    """Menambahkan data alat (tools) ke dalam tabel tools"""
    cursor = connection.cursor()

    # Daftar data contoh alat (tools) untuk maskapai Garuda
    tools_data = [
        ('Avionics Test Set', 'AT-1234', 'Alat untuk menguji sistem avionik pesawat',),
        ('Hydraulic Test Pump', 'HTP-5678', 'Alat untuk menguji sistem hidrolik pesawat',),
        ('Digital Torque Wrench', 'DTW-9876', 'Alat untuk mengukur dan mengatur torsi pada baut',),
        ('Airframe Inspection Ladder', 'AIL-4567', 'Tangga inspeksi untuk perawatan airframe pesawat',),
        ('Engine Overhaul Kit', 'EOK-2345', 'Kit lengkap untuk perawatan dan overhaul mesin pesawat',),
        ('Aircraft Jack', 'AJ-6789', 'Alat jack untuk mengangkat pesawat selama perawatan',),
        ('Cargo Loading Equipment', 'CLE-5432', 'Peralatan untuk pemuatan dan pembongkaran kargo pesawat',)
    ]

    # SQL untuk memasukkan data ke dalam tabel tools
    insert_tools_sql = """
    INSERT INTO tools (name, part_number, description)
    VALUES (%s, %s, %s);
    """

    try:
        cursor.executemany(insert_tools_sql, tools_data)
        connection.commit()
        print(f"{len(tools_data)} alat berhasil dimasukkan ke tabel tools.")
    except Error as e:
        print(f"Error saat memasukkan data ke tabel tools: {e}")

def insert_logs_data(connection):
    """Menambahkan data log (peminjaman dan pengembalian alat) ke dalam tabel logs"""
    cursor = connection.cursor()

    # List nomor registrasi pesawat (aircraft registrations)
    aircraft_regs = [
        'PK-GFS', 'PK-GFU', 'PK-GFV', 'PK-GFW', 'PK-GFX',
        'PK-GUA', 'PK-GUC', 'PK-GUD', 'PK-GUE', 'PK-GUF'
    ]

    # Daftar alat (tools) yang sudah ada dalam tabel tools
    cursor.execute("SELECT id FROM tools")
    tools = cursor.fetchall()

    # Daftar tindakan yang bisa dilakukan dalam log: 'borrow' dan 'return'
    actions = ['borrow', 'return']

    # Membuat beberapa log sebagai contoh
    logs_data = []
    for _ in range(20):  # Jumlah log yang ingin dibuat
        tool_id = random.choice(tools)[0]  # Pilih random tool_id dari tabel tools
        action = random.choice(actions)  # Pilih random action (borrow/return)
        
        # Pilih aircraft registration secara random
        aircraft_reg = random.choice(aircraft_regs)
        
        # Tanggal acak untuk calibration (misalnya dalam format YYYY-MM-DD)
        calibration_date = (datetime.now() - timedelta(days=random.randint(0, 365))).strftime('%Y-%m-%d')
        
        # Timestamp untuk log (bisa berupa string waktu acak)
        timestamp = (datetime.now() - timedelta(days=random.randint(0, 30))).strftime('%Y-%m-%d %H:%M:%S')
        
        # Data log yang akan dimasukkan
        borrower_name = f'User{random.randint(1, 5)}'  # Nama peminjam acak (misal User1, User2, dst.)
        logs_data.append((borrower_name, tool_id, timestamp, calibration_date, aircraft_reg))

    # SQL untuk memasukkan data ke dalam tabel logs
    insert_logs_sql = """
    INSERT INTO logs (borrower_name, tool_id, timestamp, calibration, aircraft_reg)
    VALUES (%s, %s, %s, %s, %s);
    """

    try:
        cursor.executemany(insert_logs_sql, logs_data)
        connection.commit()
        print(f"{len(logs_data)} log berhasil dimasukkan ke tabel logs.")
    except Error as e:
        print(f"Error saat memasukkan data ke tabel logs: {e}")


def main():
    connection = create_connection()
    if connection:
        # Pilihan untuk melakukan rollback atau migrasi
        # Drop tabel jika diperlukan
        drop_tables(connection)
        
        # Buat tabel dan akun admin
        create_tables(connection)
        create_admin_user(connection)
        insert_tools_data(connection)
        insert_logs_data(connection)

        connection.close()

if __name__ == "__main__":
    main()
