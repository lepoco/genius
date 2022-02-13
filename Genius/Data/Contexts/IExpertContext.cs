using Genius.Data.Models.Expert;
using Microsoft.EntityFrameworkCore;
using System;
using System.Threading;
using System.Threading.Tasks;

namespace Genius.Data.Contexts
{
    /// <summary>
    /// Abstraction of the database context;
    /// </summary>
    public interface IExpertContext : IDisposable
    {
        public DbSet<Models.Expert.System> Systems { get; }

        public DbSet<Product> Products { get; }

        public DbSet<Condition> Conditions { get; }

        public DbSet<Relation> Relations { get; }

        public Task<int> SaveChangesAsync(CancellationToken cancellationToken = default);
    }
}
