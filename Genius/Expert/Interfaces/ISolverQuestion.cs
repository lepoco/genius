using Genius.Data.Models.Expert;
using System.Collections.Generic;

namespace Genius.Expert.Interfaces
{
    public interface ISolverQuestion
    {
        public int SystemId { get; set; }

        public bool IsMultiple { get; set; }

        public IEnumerable<Condition> Confirming { get; set; }

        public IEnumerable<Condition> Negating { get; set; }

        public IEnumerable<Condition> Indifferent { get; set; }
    }
}